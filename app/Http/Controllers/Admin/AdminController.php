<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Cita;
use App\Models\User;
use App\Models\Role;
use App\Models\Especialidad;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $citas = Cita::with(['paciente', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $totalCitas           = Cita::count();
        $totalCitasPendientes = Cita::where('estado', 'pendiente')->count();
        $totalCitasRealizadas = Cita::where('estado', 'realizada')->count();
        $totalCitasCanceladas = Cita::where('estado', 'cancelada')->count();

        return view('admin.dashboard', compact(
            'user',
            'citas',
            'totalCitas',
            'totalCitasPendientes',
            'totalCitasRealizadas',
            'totalCitasCanceladas'
        ));
    }

    public function resumenGlobal(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('admin.dashboard');
        }

        return response()->json([
            'agendadas'   => Cita::count(),
            'completadas' => Cita::where('estado', 'realizada')->count(),
            'canceladas'  => Cita::where('estado', 'cancelada')->count(),
        ]);
    }

    public function editarPerfil()
    {
        $user = Auth::user();
        return view('admin.perfil', compact('user'));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telefono'          => ['nullable', 'digits:10'],
            'dni'               => ['required', 'digits:10', Rule::unique('users', 'dni')->ignore($user->id)],
            'direccion'         => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento'  => ['nullable', 'date', 'before:today'],
            'sexo'              => ['nullable', 'in:Masculino,Femenino,Otro'],
            'avatar'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        $messages = [
            'name.required'           => 'El nombre es obligatorio.',
            'email.required'          => 'El correo es obligatorio.',
            'email.email'             => 'Formato de correo inválido.',
            'email.unique'            => 'Este correo ya está registrado.',
            'telefono.digits'         => 'El teléfono debe tener exactamente 10 dígitos.',
            'dni.required'            => 'El número de cédula es obligatorio.',
            'dni.digits'              => 'El número de cédula debe tener exactamente 10 dígitos.',
            'dni.unique'              => 'Este número de cédula ya está registrado.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'sexo.in'                 => 'Seleccione un sexo válido.',
            'avatar.image'            => 'La foto debe ser una imagen.',
            'avatar.mimes'            => 'Formato permitido: jpg, jpeg, png o webp.',
            'avatar.max'              => 'La imagen no debe superar 2 MB.',
        ];

        $data = $request->validate($rules, $messages);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    public function usuarios(Request $request)
    {
        $buscar = trim((string)$request->get('buscar', ''));
        $perPage = (int)($request->get('per_page', 12));

        // columnas lógicas para la tabla (visor de columnas)
        $allColumns = ['usuario','contacto','rol','estado','especialidades','acciones'];
        $cols = $request->has('cols')
            ? array_values(array_intersect($allColumns, (array)$request->get('cols')))
            : ($request->session()->get('usuarios.cols') ?: $allColumns);
        if (empty($cols)) $cols = $allColumns;
        $request->session()->put('usuarios.cols', $cols);

        $usersQ = User::with(['roles', 'especialidades'])->orderBy('created_at', 'desc');

        if ($buscar !== '') {
            $like = '%'.$buscar.'%';
            $usersQ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('dni','like',$like)
                  ->orWhere('telefono','like',$like);
            });
        }

        $users = $usersQ->paginate($perPage)->appends($request->query());
        $roles = Role::orderBy('name', 'asc')->get();

        return view('admin.usuarios', compact('users', 'roles', 'buscar', 'cols', 'allColumns', 'perPage'));
    }

    public function usuariosCreate()
    {
        $roles = Role::orderBy('name')->get();
        $especialidades = Especialidad::orderBy('nombre')->get();
        return view('admin.users.create', compact('roles','especialidades'));
    }

    public function usuariosStore(Request $request)
    {
        $roles = Role::pluck('name','id'); // id => name

        $baseRules = [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed','regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
            'telefono' => ['nullable','digits:10'],
            'dni'      => ['required','digits:10','unique:users,dni'],
            'direccion'=> ['nullable','string','max:255'],
            'fecha_nacimiento' => ['nullable','date','before:today'],
            'sexo'     => ['nullable','in:Masculino,Femenino,Otro'],
            'role_id'  => ['required','exists:roles,id'],
            // doctor-only (opcionales aquí; se validan condicionalmente)
            'especialidad_id' => ['nullable','integer','exists:especialidades,id'],
            'precio_consulta' => ['nullable','numeric','min:0','max:99999999.99'],
        ];

        $data = $request->validate($baseRules);

        $roleName = $roles[(int)$data['role_id']] ?? null;
        $isDoctor = $roleName === 'doctor';

        // Si es doctor, especialidad requerida
        if ($isDoctor) {
            $request->validate([
                'especialidad_id' => ['required','integer','exists:especialidades,id'],
            ], [
                'especialidad_id.required' => 'La especialidad es obligatoria para el rol Doctor.',
            ]);
        }

        $u = new User();
        $u->name             = $data['name'];
        $u->email            = $data['email'];
        $u->password         = \Illuminate\Support\Facades\Hash::make($data['password']);
        $u->active           = true;
        $u->telefono         = $data['telefono'] ?? null;
        $u->dni              = $data['dni'];
        $u->direccion        = $data['direccion'] ?? null;
        $u->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $u->sexo             = $data['sexo'] ?? null;

        // Campos económicos
        $u->precio_consulta  = $isDoctor ? ($data['precio_consulta'] ?? null) : null;
        $u->moneda           = 'USD'; // FIJA para cumplir NOT NULL

        // Estado de cuenta inicial
        $u->status           = 'active';

        $u->save();

        // Rol
        $u->roles()->sync([(int)$data['role_id']]);

        // Especialidad solo para doctor
        if ($isDoctor) {
            $u->especialidades()->sync([(int)$request->input('especialidad_id')]);
        }

        return redirect()->route('admin.usuarios.index')->with('success','Usuario creado correctamente.');
    }


    public function usuariosShow(User $user)
    {
        $user->load(['roles','especialidades']);
        return view('admin.users.show', compact('user'));
    }

    public function usuariosEdit(User $user)
    {
        $user->load(['roles','especialidades']);
        $roles = Role::orderBy('name')->get();
        $especialidades = Especialidad::orderBy('nombre')->get();
        return view('admin.users.edit', compact('user','roles','especialidades'));
    }

    public function usuariosUpdate(Request $request, User $user)
    {
        $request->merge(['email' => strtolower($request->input('email'))]);

        $rules = [
            'name'             => ['required','string','max:255'],
            'email'            => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'telefono'         => ['nullable','digits:10'],
            'dni'              => ['required','digits:10', Rule::unique('users','dni')->ignore($user->id)],
            'direccion'        => ['nullable','string','max:255'],
            'fecha_nacimiento' => ['nullable','date','before:today'],
            'sexo'             => ['nullable','in:Masculino,Femenino,Otro'],
            'role_id'          => ['required','exists:roles,id'],
            'especialidad_id'  => ['nullable','integer','exists:especialidades,id'],
            'precio_consulta'  => ['nullable','numeric','min:0','max:99999999.99'],
        ];
        $data = $request->validate($rules);

        // Datos básicos
        $user->name             = $data['name'];
        $user->email            = $data['email'];
        $user->telefono         = $data['telefono'] ?? null;
        $user->dni              = $data['dni'] ?? null;
        $user->direccion        = $data['direccion'] ?? null;
        $user->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $user->sexo             = $data['sexo'] ?? null;

        // Rol y lógica asociada
        $roleId   = (int)$data['role_id'];
        $roleName = optional(\App\Models\Role::find($roleId))->name;
        $isDoctor = $roleName === 'doctor';

        if ($isDoctor) {
            // Doctor: puede tener precio y especialidad
            $user->precio_consulta = $data['precio_consulta'] ?? $user->precio_consulta;
            // IMPORTANTE: columna NOT NULL → asegura valor
            $user->moneda = $user->moneda ?: 'USD';

            if (!empty($data['especialidad_id'])) {
                $user->especialidades()->sync([(int)$data['especialidad_id']]);
            } else {
                $user->especialidades()->sync([]);
            }
        } else {
            // No doctor: sin precio ni especialidad
            $user->precio_consulta = null;
            $user->especialidades()->sync([]);
            // NO pongas null en NOT NULL
            $user->moneda = $user->moneda ?: 'USD';
        }

        $user->save();

        // Sincr. de rol
        $user->roles()->sync([$roleId]);

        return redirect()
            ->route('admin.usuarios.edit', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function usuarioDestroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['No puedes eliminar tu propio usuario.']);
        }

        if ($user->hasRole('administrador')) {
            return back()->withErrors(['No puedes eliminar cuentas con rol Administrador.']);
        }

        $user->roles()->detach();
        $user->delete();

        return back()->with('success', 'Usuario eliminado.');
    }

    public function crearDoctor()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();
        return view('admin.doctor-create', compact('especialidades'));
    }

    public function storeDoctor(Request $request)
    {
        return $this->guardarDoctor($request);
    }

    public function guardarDoctor(Request $request)
    {
        $rules = [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
            'telefono'         => ['nullable', 'digits:10'],
            'dni'              => ['required', 'digits:10', 'unique:users,dni'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo'             => ['nullable', 'in:Masculino,Femenino,Otro'],
            'avatar'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'especialidad_id'  => ['required', 'integer', 'exists:especialidades,id'],
            'precio_consulta'  => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'moneda'           => ['nullable', 'in:USD'],
        ];

        $messages = [
            'required'                => 'El :attribute es obligatorio.',
            'email'                   => 'Ingresa un correo válido.',
            'unique'                  => 'Este :attribute ya está registrado.',
            'password.min'            => 'La contraseña debe tener al menos 8 caracteres.',
            'confirmed'               => 'La confirmación de :attribute no coincide.',
            'password.regex'          => 'La contraseña debe incluir letras y números.',
            'digits'                  => 'El :attribute debe tener exactamente :digits dígitos.',
            'in'                      => 'Selecciona un valor válido para :attribute.',
            'date'                    => 'La :attribute no es válida.',
            'before'                  => 'La :attribute debe ser anterior a hoy.',
            'image'                   => 'La :attribute debe ser una imagen.',
            'mimes'                   => 'La :attribute debe ser jpg, jpeg, png o webp.',
            'max'                     => 'La :attribute no debe superar :max.',
            'integer'                 => 'Selecciona una especialidad válida.',
            'exists'                  => 'La especialidad seleccionada no existe.',
            'dni.required'            => 'El número de cédula es obligatorio.',
            'dni.digits'              => 'El número de cédula debe tener exactamente 10 dígitos.',
            'dni.unique'              => 'Este número de cédula ya está registrado.',
            'precio_consulta.numeric' => 'El precio debe ser numérico.',
            'precio_consulta.min'     => 'El precio no puede ser negativo.',
            'moneda.in'               => 'Moneda inválida (fijo: USD).',
        ];

        $attributes = [
            'name'                  => 'nombre',
            'email'                 => 'correo',
            'password'              => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'telefono'              => 'teléfono',
            'dni'                   => 'número de cédula',
            'direccion'             => 'dirección',
            'fecha_nacimiento'      => 'fecha de nacimiento',
            'sexo'                  => 'sexo',
            'avatar'                => 'foto',
            'especialidad_id'       => 'especialidad',
            'precio_consulta'       => 'precio de consulta',
            'moneda'                => 'moneda',
        ];

        $validated = request()->validate($rules, $messages, $attributes);

        $user = new User();
        $user->name             = $validated['name'];
        $user->email            = $validated['email'];
        $user->password         = Hash::make($validated['password']);
        $user->active           = true;
        $user->telefono         = $validated['telefono'] ?? null;
        $user->dni              = $validated['dni'] ?? null;
        $user->direccion        = $validated['direccion'] ?? null;
        $user->fecha_nacimiento = $validated['fecha_nacimiento'] ?? null;
        $user->sexo             = $validated['sexo'] ?? null;
        $user->precio_consulta  = $validated['precio_consulta'] ?? null;
        $user->moneda           = 'USD';

        if (request()->hasFile('avatar')) {
            $user->avatar = request()->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        $role = Role::where('name', 'doctor')->firstOrFail();
        $user->roles()->sync([$role->id]);
        $user->especialidades()->sync([$validated['especialidad_id']]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Doctor creado correctamente.');
    }

    public function doctoresPorEspecialidad(Especialidad $especialidad)
    {
        $doctores = $especialidad->doctores()
            ->whereHas('roles', fn($q) => $q->where('name', 'doctor'))
            ->where('active', true)
            ->orderBy('name')
            ->get(['users.id', 'users.name']);

        return response()->json($doctores);
    }

    public function crearPaciente()
    {
        return view('admin.paciente-create');
    }

    public function storePaciente(Request $request)
    {
        $rules = [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
            'telefono'         => ['nullable', 'digits:10'],
            'dni'              => ['required', 'digits:10', 'unique:users,dni'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'sexo'             => ['nullable', 'in:Masculino,Femenino,Otro'],
        ];

        $messages = [
            'required'       => 'El :attribute es obligatorio.',
            'email'          => 'Ingresa un correo válido.',
            'unique'         => 'Este :attribute ya está registrado.',
            'digits'         => 'El :attribute debe tener exactamente :digits dígitos.',
            'password.min'   => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe incluir letras y números.',
            'confirmed'      => 'La confirmación de :attribute no coincide.',
            'before'         => 'La :attribute debe ser anterior a hoy.',
            'in'             => 'Selecciona un valor válido para :attribute.',
        ];

        $attributes = [
            'name'                  => 'nombre',
            'email'                 => 'correo',
            'password'              => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'telefono'              => 'teléfono',
            'dni'                   => 'número de cédula',
            'direccion'             => 'dirección',
            'fecha_nacimiento'      => 'fecha de nacimiento',
            'sexo'                  => 'sexo',
        ];

        $data = $request->validate($rules, $messages, $attributes);

        $user = new User();
        $user->name             = $data['name'];
        $user->email            = $data['email'];
        $user->password         = Hash::make($data['password']);
        $user->active           = true;
        $user->telefono         = $data['telefono'] ?? null;
        $user->dni              = $data['dni'];
        $user->direccion        = $data['direccion'] ?? null;
        $user->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $user->sexo             = $data['sexo'] ?? null;
        $user->status           = 'active';
        $user->save();

        $role = Role::where('name', 'paciente')->firstOrFail();
        $user->roles()->sync([$role->id]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Paciente creado correctamente.');
    }

    private function roleNameById(?int $roleId): ?string
    {
        if (!$roleId) return null;
        return optional(Role::find($roleId))->name;
    }

    // ===== Exportes =====
    public function usuariosExportExcel(Request $request)
    {
        $buscar = trim((string)$request->get('buscar',''));
        $usersQ = User::with(['roles','especialidades'])->orderBy('id','desc');

        if ($buscar !== '') {
            $like = '%'.$buscar.'%';
            $usersQ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('dni','like',$like)
                  ->orWhere('telefono','like',$like);
            });
        }

        $users = $usersQ->get();

        $sheetData = [];
        $sheetData[] = ['ID','Nombre','Email','Teléfono','Cédula','Rol','Estado','Suspendido Hasta','Último acceso','Creado','Especialidades'];

        foreach ($users as $u) {
            $rol = optional($u->roles->first())->name;
            $esp = ($u->especialidades ?? collect())->pluck('nombre')->implode(', ');
            $sheetData[] = [
                $u->id,
                $u->name,
                $u->email,
                $u->telefono,
                $u->dni,
                $rol,
                $u->status ?? 'active',
                optional($u->suspended_until)?->format('Y-m-d H:i'),
                optional($u->last_login_at)?->format('Y-m-d H:i'),
                optional($u->created_at)?->format('Y-m-d H:i'),
                $esp,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($sheetData, null, 'A1', true);
        foreach (range('A','L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->setTitle('Usuarios');

        $writer = new Xlsx($spreadsheet);
        $filename = 'usuarios_'.now()->format('Ymd_His').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function usuariosExportPdf(Request $request)
    {
        $buscar = trim((string)$request->get('buscar',''));
        $usersQ = User::with(['roles','especialidades'])->orderBy('id','desc');

        if ($buscar !== '') {
            $like = '%'.$buscar.'%';
            $usersQ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('dni','like',$like)
                  ->orWhere('telefono','like',$like);
            });
        }

        $users = $usersQ->get();

        $html = view('admin.users.usuarios-pdf', compact('users'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream('usuarios_'.now()->format('Ymd_His').'.pdf');
        exit;
    }
}
