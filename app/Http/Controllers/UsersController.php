<?php

namespace Vanguard\Http\Controllers;

use Vanguard\Events\User\Banned;
use Vanguard\Events\User\Deleted;
use Vanguard\Events\User\TwoFactorDisabledByAdmin;
use Vanguard\Events\User\TwoFactorEnabledByAdmin;
use Vanguard\Events\User\UpdatedByAdmin;
use Vanguard\Http\Requests\User\CreateUserRequest;
use Vanguard\Http\Requests\User\EnableTwoFactorRequest;
use Vanguard\Http\Requests\User\UpdateDetailsRequest;
use Vanguard\Http\Requests\User\UpdateLoginDetailsRequest;
use Vanguard\Http\Requests\User\UpdateUserRequest;
use Vanguard\Repositories\Activity\ActivityRepository;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Services\Upload\UserAvatarManager;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;
use Vanguard\Role;
use Vanguard\Profesion;
use Vanguard\Cargo;
use Vanguard\Oficina;
use Vanguard\Pais;
use PDF;
use Auth;
use Authy;
use Entrust;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

/**
 * Class UsersController
 * @package Vanguard\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->middleware('auth');
        $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        $this->middleware('permission:ver-colegas-todos|ver-colegas-oficina|ver-colegas-solo');
        $this->users = $users;
    }

    /**
     * Display paginated list of all users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        

        //$users = $this->users->paginate($perPage, Input::get('search'), Input::get('status'));
        //$statuses = ['' => trans('app.all')] + UserStatus::lists();
        
        //$users=User::orderBy('id','desc')->where('status',1)->get();
        
        if (Entrust::can('ver-colegas-todos')) {
            $users = User::orderBy('id','desc')->get();
        }
        //si tiene permiso para ver colegas de la oficina
        if (Entrust::can('ver-colegas-oficina')) {
            $oficina_id=auth()->user()->oficina_id;
            $users=Role::with('users')
            ->where('name','Colega')->first()->users
            ->where('oficina_id',$oficina_id)
           ;   
        }

        if (Entrust::can('ver-colegas-solo')) {
            $users = User::where('id', auth()->user()->id)->get();
        }
        return view('user.list',compact('users'));
    }

    /**
     * Displays user profile page.
     *
     * @param User $user
     * @param ActivityRepository $activities
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(User $user, ActivityRepository $activities)
    {
        $socialNetworks = $user->socialNetworks;

        $userActivities = $activities->getLatestActivitiesForUser($user->id, 10);
        
        return view('user.view', compact('user', 'socialNetworks', 'userActivities'));
    }

    /**
     * Displays form for creating a new user.
     *
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CountryRepository $countryRepository, RoleRepository $roleRepository)
    {
        
        $roles = Role::lists('name','id');      

        return view('user.add', compact('roles'));
    }

    /**
     * Stores new user into the database.
     *
     * @param CreateUserRequest $request
     * @return mixed
     */
    public function validacion_ajax(CreateUserRequest $request)
    {
        //dd($request->all());
        /*$validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',            
            'password' => 'required|min:6|confirmed',            
            'role' => 'required|exists:roles,id'
        ]);

        if ($validator->passes()) {
            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);*/
        return response()->json(['success'=>'success']);
    }
    
    public function store(Request $request)
    {   
        
        
        // When user is created by administrator, we will set his
        // status to Active by default.
        //$data = $request->all() + ['status' => UserStatus::ACTIVE];

        // Username should be updated only if it is provided.
        // So, if it is an empty string, then we just leave it as it is.
       
        $data['username'] = null;       
        
         
        $user = $this->users->create($request->all()); 
        //dd($user);   
        //$user->save();

        //$this->users->updateSocialNetworks($user->id, []);
        $this->users->setRole($user->id, $request->get('role'));
        
        return redirect()->route('user.list')
            ->withSuccess(trans('app.user_created'));
    }

    /**
     * Displays edit user form.
     *
     * @param User $user
     * @param CountryRepository $countryRepository
     * @param RoleRepository $roleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user/*, CountryRepository $countryRepository*/, RoleRepository $roleRepository, Request $request)
    {
        if ($request->profile == 1) {

            $profile = true;
        }
        else {

            $profile = false;
        }
        $edit = true;
        $oficina=auth()->user()->oficina_id;
        
        if (Entrust::hasRole(['Administradora']) && $user->oficina_id!=$oficina) {

            return redirect()->route('user.list')
                ->withErrors('Ese usuario no pertenece a esta oficina,no puedes modificarlo');
            
        }
        //$countries = $countryRepository->lists();
        //$socials = $user->socialNetworks;
        $roles = $roleRepository->lists();
        $statuses = UserStatus::lists();
        //$socialLogins = $this->users->getUserSocialLogins($user->id);
        $anho_entrante=$user->created_at->format('Y');
        $meses=
        ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        
        $acumulado_mes=$user->acumulado->where('oficina_id',$user->oficina_id);
        
        $pais=$user->oficina->pais;
        $m_a="Enero-2018";
        //dd("$acumulado_mes->where('m_a',$m_a)->first()->indemnizacion");
        //dd($user->roles->first()->id);
        
        
        
        return view('user.edit',
            compact('profile', 'edit', 'user','roles', 'statuses','anho_entrante','meses','acumulado_mes','pais'));
    }

    /**
     * Updates user details.
     *
     * @param User $user
     * @param UpdateDetailsRequest $request
     * @return mixed
     */
    public function updateDetails(User $user, UpdateDetailsRequest $request)
    {
        $this->users->update($user->id, $request->all());
        $this->users->setRole($user->id, $request->get('role'));       
        

        event(new UpdatedByAdmin($user));

        // If user status was updated to "Banned",
        // fire the appropriate event.
        if ($this->userIsBanned($user, $request)) {
            event(new Banned($user));
        }

        return redirect()->route('user.list')
            ->withSuccess(trans('app.user_updated'));
    }

    /**
     * Check if user is banned during last update.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    private function userIsBanned(User $user, Request $request)
    {
        return $user->status != $request->status && $request->status == UserStatus::BANNED;
    }

    /**
     * Update user's avatar from uploaded image.
     *
     * @param User $user
     * @param UserAvatarManager $avatarManager
     * @return mixed
     */
    public function updateAvatar(User $user, UserAvatarManager $avatarManager)
    {
        $name = $avatarManager->uploadAndCropAvatar($user);

        $this->users->update($user->id, ['avatar' => $name]);

        event(new UpdatedByAdmin($user));

        return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.avatar_changed'));
    }

    //vista para subir la firma del colega
    
    public function firmaColega(User $user)
    {
        $edit = true;
        return view('user.firma_colega', compact('user', 'edit'));
    }

    public function updateFirma(User $user, UserAvatarManager $avatarManager)
        {
            $name = $avatarManager->uploadAndCropAvatar($user);

            $this->users->update($user->id, ['firma' => $name]);

            event(new UpdatedByAdmin($user));

            if (Entrust::hasRole(['Colega', 'Colaborador'])) 
            {
                return redirect()->route('user.firma', $user->id)
                    ->withSuccess('Se ha actualizado la firma digital');
            }

            return redirect()->route('user.edit', $user->id)
                ->withSuccess('Se ha actualizado la firma digital');
        }
    /**
     * Update user's avatar from some external source (Gravatar, Facebook, Twitter...)
     *
     * @param User $user
     * @param Request $request
     * @param UserAvatarManager $avatarManager
     * @return mixed
     */
    public function updateAvatarExternal(User $user, Request $request, UserAvatarManager $avatarManager)
    {
        $avatarManager->deleteAvatarIfUploaded($user);

        $this->users->update($user->id, ['firma' => $request->get('url')]);

        event(new UpdatedByAdmin($user));

        return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.avatar_changed'));
    }

    /**
     * Update user's social networks.
     *
     * @param User $user
     * @param Request $request
     * @return mixed
     */
    public function updateSocialNetworks(User $user, Request $request)
    {
        $this->users->updateSocialNetworks($user->id, $request->get('socials'));

        event(new UpdatedByAdmin($user));

        return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.socials_updated'));
    }

    /**
     * Update user's login details.
     *
     * @param User $user
     * @param UpdateLoginDetailsRequest $request
     * @return mixed
     */
    public function updateLoginDetails(User $user, UpdateLoginDetailsRequest $request)
    {
        $data = $request->all();

        if (trim($data['password']) == '') {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        $this->users->update($user->id, $data);

        event(new UpdatedByAdmin($user));
        $ruta=Entrust::hasRole('Colega')?'user.password':'user.edit';
        return redirect()->route($ruta, $user->id)
            ->withSuccess(trans('app.login_updated'));
    }

    /**
     * Removes the user from database.
     *
     * @param User $user
     * @return $this
     */
    public function delete(User $user)
    {
        if ($user->id == Auth::id()) {
            return redirect()->route('user.list')
                ->withErrors(trans('app.you_cannot_delete_yourself'));
        }
        $user->status='desactive';
        $user->save();
        
        //$this->users->delete($user->id);

        //event(new Deleted($user));

        return redirect()->route('user.list')
            ->withSuccess(trans('app.user_deleted'));
    }

    /**
     * Enables Authy Two-Factor Authentication for user.
     *
     * @param User $user
     * @param EnableTwoFactorRequest $request
     * @return $this
     */
    /*public function enableTwoFactorAuth(User $user, EnableTwoFactorRequest $request)
    {
        if (Authy::isEnabled($user)) {
            return redirect()->route('user.edit', $user->id)
                ->withErrors(trans('app.2fa_already_enabled_user'));
        }

        $user->setAuthPhoneInformation($request->country_code, $request->phone_number);

        Authy::register($user);

        $user->save();

        event(new TwoFactorEnabledByAdmin($user));

        return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.2fa_enabled'));
    }*/

    /**
     * Disables Authy Two-Factor Authentication for user.
     *
     * @param User $user
     * @return $this
     */
    /*public function disableTwoFactorAuth(User $user)
    {
        if (! Authy::isEnabled($user)) {
            return redirect()->route('user.edit', $user->id)
                ->withErrors(trans('app.2fa_not_enabled_user'));
        }

        Authy::delete($user);

        $user->save();

        event(new TwoFactorDisabledByAdmin($user));

        return redirect()->route('user.edit', $user->id)
            ->withSuccess(trans('app.2fa_disabled'));
    }*/


    /**
     * Displays the list with all active sessions for selected user.
     *
     * @param User $user
     * @param SessionRepository $sessionRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sessions(User $user, SessionRepository $sessionRepository)
    {
        $adminView = true;
        $sessions = $sessionRepository->getUserSessions($user->id);

        return view('user.sessions', compact('sessions', 'user', 'adminView'));
    }

    /**
     * Invalidate specified session for selected user.
     *
     * @param User $user
     * @param $sessionId
     * @param SessionRepository $sessionRepository
     * @return mixed
     */
    public function invalidateSession(User $user, $sessionId, SessionRepository $sessionRepository)
    {
        $sessionRepository->invalidateUserSession($user->id, $sessionId);

        return redirect()->route('user.sessions', $user->id)
            ->withSuccess(trans('app.session_invalidated'));
    }
    /*public function liquidar_empleado(Request $request,$id)
    {
        $user=User::find($id);
            
        foreach ($user->acumulado as $mes) {
            
            $mes->indemnizacion=$request->indemnizacion["$mes->m_a"];
            $mes->pension=$request->pension["$mes->m_a"];
            $mes->save();
            
        }
        $user->status=0;
        $user->save();

        return redirect()->route('user.list')
            ->withSuccess('Usuario desactivado con exito');
    }*/
    public function password()
    {
        $edit=true;
        $user=auth()->user();
        //dd($user);
        return view('user.password',compact('user','edit'));
    }

    public function descargarDatosColega(User $user)
    {
        $pdf = PDF::loadView('user.pdf_datos_colega', compact('user'));

        return $pdf->download("Datos de ".$user->first_name." ".$user->last_name.".pdf");
    }
}