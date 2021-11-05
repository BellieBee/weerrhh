<?php

/**
 * Authentication
 */


Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');

Route::get('logout', [
    'as' => 'auth.logout',
    'uses' => 'Auth\AuthController@getLogout'
]);

// Allow registration routes only if registration is enabled.
if (settings('reg_enabled')) {
    Route::get('register', 'Auth\AuthController@getRegister');
    Route::post('register', 'Auth\AuthController@postRegister');
    Route::get('register/confirmation/{token}', [
        'as' => 'register.confirm-email',
        'uses' => 'Auth\AuthController@confirmEmail'
    ]);
}

// Register password reset routes only if it is enabled inside website settings.
if (settings('forgot_password')) {
    Route::get('password/remind', 'Auth\PasswordController@forgotPassword');
    Route::post('password/remind', 'Auth\PasswordController@sendPasswordReminder');
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
}

/**
 * Two-Factor Authentication
 */
if (settings('2fa.enabled')) {
    Route::get('auth/two-factor-authentication', [
        'as' => 'auth.token',
        'uses' => 'Auth\AuthController@getToken'
    ]);

    Route::post('auth/two-factor-authentication', [
        'as' => 'auth.token.validate',
        'uses' => 'Auth\AuthController@postToken'
    ]);
}

/**
 * Social Login
 */
Route::get('auth/{provider}/login', [
    'as' => 'social.login',
    'uses' => 'Auth\SocialAuthController@redirectToProvider',
    'middleware' => 'social.auth|login'
]);

Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

Route::get('auth/twitter/email', 'Auth\SocialAuthController@getTwitterEmail');
Route::post('auth/twitter/email', 'Auth\SocialAuthController@postTwitterEmail');

/**
 * Other
 */

Route::get('/', [
    'as' => 'dashboard',
    'uses' => 'DashboardController@index'
]);

/**
 * User Profile
 */

Route::get('profile', [
    'as' => 'profile',
    'uses' => 'ProfileController@index'
]);

Route::get('profile/activity', [
    'as' => 'profile.activity',
    'uses' => 'ProfileController@activity'
]);

Route::put('profile/details/update', [
    'as' => 'profile.update.details',
    'uses' => 'ProfileController@updateDetails'
]);

Route::post('profile/avatar/update', [
    'as' => 'profile.update.avatar',
    'uses' => 'ProfileController@updateAvatar'
]);

Route::post('profile/avatar/update/external', [
    'as' => 'profile.update.avatar-external',
    'uses' => 'ProfileController@updateAvatarExternal'
]);

Route::put('profile/login-details/update', [
    'as' => 'profile.update.login-details',
    'uses' => 'ProfileController@updateLoginDetails'
]);

Route::put('profile/social-networks/update', [
    'as' => 'profile.update.social-networks',
    'uses' => 'ProfileController@updateSocialNetworks'
]);

Route::post('profile/two-factor/enable', [
    'as' => 'profile.two-factor.enable',
    'uses' => 'ProfileController@enableTwoFactorAuth'
]);

Route::post('profile/two-factor/disable', [
    'as' => 'profile.two-factor.disable',
    'uses' => 'ProfileController@disableTwoFactorAuth'
]);

Route::get('profile/sessions', [
    'as' => 'profile.sessions',
    'uses' => 'ProfileController@sessions',
    'middleware' => 'permission:ver-sesiones-activas'
]);

Route::delete('profile/sessions/{session}/invalidate', [
    'as' => 'profile.sessions.invalidate',
    'uses' => 'ProfileController@invalidateSession'
]);

/**
 * User Management
 */
Route::get('user', [
    'as' => 'user.list',
    'uses' => 'UsersController@index',
     'middleware' => 'permission:ver-colegas-todos|ver-colegas-oficina|ver-colegas-solo'
]);

Route::get('user/create', [
    'as' => 'user.create',
    'uses' => 'UsersController@create',
    'middleware' => 'permission:crear-colegas|crear-colegas-oficina|crear-usuario-todos|crear-usuario-colega|crear-usuario-colega-consultor'
]);

Route::post('user/create', [
    'as' => 'user.store',
    'uses' => 'UsersController@store',
    'middleware' => 'permission:crear-colegas|crear-colegas-oficina|crear-usuario-todos|crear-usuario-colega|crear-usuario-colega-consultor'
]);
Route::post('user/validacion', [
    'as' => 'user.validacion',
    'uses' => 'UsersController@validacion_ajax',    
]);
Route::get('user/{user}/show', [
    'as' => 'user.show',
    'uses' => 'UsersController@view',
    'middleware' => 'permission:ver-colegas-detalle'
]);

Route::get('user/{user}/edit', [
    'as' => 'user.edit',
    'uses' => 'UsersController@edit',
     'middleware' => 'permission:editar-colegas|ver-perfil-administrativo'
]);

Route::put('user/{user}/update/details', [
    'as' => 'user.update.details',
    'uses' => 'UsersController@updateDetails',
    'middleware' => 'permission:editar-colegas'
]);

Route::put('user/{user}/update/login-details', [
    'as' => 'user.update.login-details',
    'uses' => 'UsersController@updateLoginDetails'
]);

Route::delete('user/{user}/delete', [
    'as' => 'user.delete',
    'uses' => 'UsersController@delete'
]);

Route::get('user/{user}/descargar', [
    'as' => 'user.descargar',
    'uses' => 'UsersController@descargarDatosColega',
    'middleware' => 'permission:descargar-colega-pdf'
]);

/*Route::post('user/{user}/update/avatar', [
    'as' => 'user.update.avatar',
    'uses' => 'UsersController@updateAvatar'
]);*/
Route::post('user/{user}/update/firma', [
    'as' => 'user.update.firma',
    'uses' => 'UsersController@updateFirma'
]);
//vista para la firma de los colegas

Route::get('user/{user}/firma', [
    'as' => 'user.firma',
    'uses' => 'UsersController@firmaColega',
    'middleware' => 'permission:ver-colegas-solo|ver-firma-solo'
]);

Route::post('user/{user}/update/avatar/external', [
    'as' => 'user.update.avatar.external',
    'uses' => 'UsersController@updateAvatarExternal'
]);

Route::post('user/{user}/update/social-networks', [
    'as' => 'user.update.socials',
    'uses' => 'UsersController@updateSocialNetworks'
]);

Route::get('user/{user}/sessions', [
    'as' => 'user.sessions',
    'uses' => 'UsersController@sessions'
]);

Route::delete('user/{user}/sessions/{session}/invalidate', [
    'as' => 'user.sessions.invalidate',
    'uses' => 'UsersController@invalidateSession'
]);

Route::post('user/{user}/two-factor/enable', [
    'as' => 'user.two-factor.enable',
    'uses' => 'UsersController@enableTwoFactorAuth'
]);

Route::post('user/{user}/two-factor/disable', [
    'as' => 'user.two-factor.disable',
    'uses' => 'UsersController@disableTwoFactorAuth'
]);

Route::post('user/liquidacion/{id}/desactivacion', [
    'as' => 'user.liquidacion.desactivacion',
    'uses' => 'UsersController@liquidar_empleado'

]);

Route::get('user/{user}/password', [
    'as' => 'user.password',
    'uses' => 'UsersController@password'
]);

/**
 * Roles & Permissions
 */

Route::get('role', [
    'as' => 'role.index',
    'uses' => 'RolesController@index',
]);

Route::get('role/create', [
    'as' => 'role.create',
    'uses' => 'RolesController@create'
]);

Route::post('role/store', [
    'as' => 'role.store',
    'uses' => 'RolesController@store'
]);

Route::get('role/{role}/edit', [
    'as' => 'role.edit',
    'uses' => 'RolesController@edit'
]);

Route::put('role/{role}/update', [
    'as' => 'role.update',
    'uses' => 'RolesController@update'
]);

Route::delete('role/{role}/delete', [
    'as' => 'role.delete',
    'uses' => 'RolesController@delete'
]);


Route::post('permission/save', [
    'as' => 'permission.save',
    'uses' => 'PermissionsController@saveRolePermissions'
]);

Route::resource('permission', 'PermissionsController');

/**
 * Settings
 */

Route::get('settings', [
    'as' => 'settings.general',
    'uses' => 'SettingsController@general',
    'middleware' => 'permission:auth|settings.general'
]);

Route::post('settings/general', [
    'as' => 'settings.general.update',
    'uses' => 'SettingsController@update',
    'middleware' => 'permission:auth|settings.general'
]);

Route::get('settings/auth', [
    'as' => 'settings.auth',
    'uses' => 'SettingsController@auth',
    'middleware' => 'permission:auth|settings.auth'
]);

Route::post('settings/auth', [
    'as' => 'settings.auth.update',
    'uses' => 'SettingsController@update',
    'middleware' => 'permission:auth|settings.auth'
]);

// Only allow managing 2FA if AUTHY_KEY is defined inside .env file
if (env('AUTHY_KEY')) {
    Route::post('settings/auth/2fa/enable', [
        'as' => 'settings.auth.2fa.enable',
        'uses' => 'SettingsController@enableTwoFactor',
        'middleware' => 'permission:auth|settings.auth'
    ]);

    Route::post('settings/auth/2fa/disable', [
        'as' => 'settings.auth.2fa.disable',
        'uses' => 'SettingsController@disableTwoFactor',
        'middleware' => 'permission:auth|settings.auth'
    ]);
}

Route::post('settings/auth/registration/captcha/enable', [
    'as' => 'settings.registration.captcha.enable',
    'uses' => 'SettingsController@enableCaptcha',
    'middleware' => 'permission:auth|settings.auth'
]);

Route::post('settings/auth/registration/captcha/disable', [
    'as' => 'settings.registration.captcha.disable',
    'uses' => 'SettingsController@disableCaptcha',
    'middleware' => 'permission:auth|settings.auth'
]);

Route::get('settings/notifications', [
    'as' => 'settings.notifications',
    'uses' => 'SettingsController@notifications',
    'middleware' => 'permission:auth|settings.notifications'
]);

Route::post('settings/notifications', [
    'as' => 'settings.notifications.update',
    'uses' => 'SettingsController@update',
    'middleware' => 'permission:auth|settings.notifications'
]);

/**
 * Activity Log
 */

Route::get('activity', [
    'as' => 'activity.index',
    'uses' => 'ActivityController@index'
]);

Route::get('activity/user/{user}/log', [
    'as' => 'activity.user',
    'uses' => 'ActivityController@userActivity'
]);

/**
 * Installation
 */

/*$router->get('install', [
    'as' => 'install.start',
    'uses' => 'InstallController@index'
]);

$router->get('install/requirements', [
    'as' => 'install.requirements',
    'uses' => 'InstallController@requirements'
]);

$router->get('install/permissions', [
    'as' => 'install.permissions',
    'uses' => 'InstallController@permissions'
]);

$router->get('install/database', [
    'as' => 'install.database',
    'uses' => 'InstallController@databaseInfo'
]);

$router->get('install/start-installation', [
    'as' => 'install.installation',
    'uses' => 'InstallController@installation'
]);

$router->post('install/start-installation', [
    'as' => 'install.installation',
    'uses' => 'InstallController@installation'
]);

$router->post('install/install-app', [
    'as' => 'install.install',
    'uses' => 'InstallController@install'
]);

$router->get('install/complete', [
    'as' => 'install.complete',
    'uses' => 'InstallController@complete'
]);

$router->get('install/error', [
    'as' => 'install.error',
    'uses' => 'InstallController@error'
]);
*/
///////////////////////////////////////////////////////

Route::get('/planilla/normal', [
    'as' => 'planilla.normal',
    'uses' => 'PlanillaController@index',
    'middleware' => 'permission:ver-planillas-todos|ver-planillas-oficina'
]);

Route::get('/crear/planilla', [
    'as' => 'planilla.crear',
    'uses' => 'PlanillaController@crear',
    'middleware' => 'permission:crear-planillas'
]);

Route::get('/edit/planilla/{id}', [
    'as' => 'planilla.edit',
    'uses' => 'PlanillaController@edit',
    'middleware' => 'permission:editar-planillas'    

]);

Route::post('/store/planilla', [
    'as' => 'planilla.crear',
    'uses' => 'PlanillaController@store',
    'middleware' => 'permission:crear-planillas|editar-planillas'

]);

Route::delete('/delete/planilla/{id}', [
    'as' => 'planilla.delete',
    'uses' => 'PlanillaController@delete',
    'middleware' => 'permission:eliminar-planillas'
]);

Route::get('/aprobacion/planilla/{id}', [
    'as' => 'planilla.aprobacion',
    'uses' => 'PlanillaController@aprobacion',
    'middleware' => 'permission:aprobar-planillas-coord|aprobar-planillas-direc'
]);

Route::get('/anulacion/planilla/{id}', [
    'as' => 'planilla.anulacion',
    'uses' => 'PlanillaController@anulacion',
    'middleware' => 'permission:anular-planillas-coord|anular-planillas-direc'
]);

Route::get('/descargar/planilla/{id}', [
    'as' => 'planilla.descargar',
    'uses' => 'PlanillaController@descargar_planilla',
    'middleware' => 'permission:descargar-planillas'
]);

Route::post('/seguro_social/planilla', [
    'as' => 'seguro.planilla',
    'uses' => 'PlanillaController@seguroSocial',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

///// REPORTES

Route::get('/reportes', [
    'as' => 'reportes',
    'uses' => 'ReportesController@index',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/planillas', [
    'as' => 'reporte.planillas',
    'uses' => 'ReportesController@reporte_planillas',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/empleado', [
    'as' => 'reporte.empleado',
    'uses' => 'ReportesController@boleta_empleados',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/vacaciones_permisos', [
    'as' => 'reporte.vacaciones_permisos',
    'uses' => 'ReportesController@reportes_vacaciones_permisos',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/liquidacion', [
    'as' => 'reporte.liquidacion',
    'uses' => 'ReportesController@boleta_liquidacion',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::get('/reporte/ajax/liquidacion', [
    'as' => 'reporte.ajax.liquidacion',
    'uses' => 'ReportesController@ajax_liquidacion',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/contratos', [
    'as' => 'reporte.contratos',
    'uses' => 'ReportesController@contratos',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('reporte/calendar/descargar', [
    'as' => 'reporte.calendar.descargar',
    'uses' => 'ReportesController@export_integrador',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('reporte/dias_vacaciones', [
    'as' => 'reporte.dias_vacaciones',
    'uses' => 'ReportesController@dias_vacaciones',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('reporte/colegas', [
    'as' => 'reporte.colegas',
    'uses' => 'ReportesController@colegas',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);

Route::post('/reporte/compras', [
    'as' => 'reporte.compras',
    'uses' => 'ReportesController@compras',
    'middleware' => 'permission:ver-reportes-todos|ver-reportes-oficina'
]);


///// AJUSTES
Route::get('ajustes', [
    'as' => 'ajustes',
    'uses' => 'AjustesController@index',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::post('ajustes', [
    'as' => 'ajustes',
    'uses' => 'AjustesController@store',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::delete('delete/cargo/{id}', [
    'as' => 'delete.cargo',
    'uses' => 'AjustesController@delete_cargo',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::delete('delete/profesion/{id}', [
    'as' => 'delete.profesion',
    'uses' => 'AjustesController@delete_profesion',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::delete('delete/motivo/{id}', [
    'as' => 'delete.motivo',
    'uses' => 'AjustesController@delete_motivo',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::get('create/cargo_profesion', [
    'as' => 'create.cargo_profesion',
    'uses' => 'AjustesController@create_cargo_profesion',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::get('create/motivo_permiso', [
    'as' => 'create.motivo_permiso',
    'uses' => 'AjustesController@create_cargo_profesion',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::get('create/tipodoc_compra', [
    'as' => 'create.tipodoc_compra',
    'uses' => 'AjustesController@create_tipodoc_compra',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);
Route::delete('delete/tipodoc/{id}', [
    'as' => 'delete.tipodoc',
    'uses' => 'AjustesController@delete_tipodoc',
    'middleware' => 'permission:ver-ajustes-todos|ver-ajustes-oficina'
]);




///// PERMISOS
Route::get('permisos', [
    'as' => 'permisos.list',
    'uses' => 'PermisosAusenciasController@index',
    'middleware' => 'permission:ver-permisosausencias-todos|ver-permisosausencias-oficina|ver-permisosausencias-solo'
]);

Route::get('create/permiso', [
    'as' => 'create.permiso',
    'uses' => 'PermisosAusenciasController@create',
    'middleware'=> 'permission:crear-permisosausencias|crear-permisosausencias-solo'    
]);

Route::get('edit/permiso/{id}', [
    'as' => 'edit.permiso',
    'uses' => 'PermisosAusenciasController@edit',
    'middleware' => 'permission:editar-permisosausencias|ver-permisosausencias-info'
]);

Route::post('store/permiso', [
    'as' => 'store.permiso',
    'uses' => 'PermisosAusenciasController@store',
    'middleware'=> 'permission:crear-permisosausencias|crear-permisosausencias-solo|editar-permisosausencias'
]);

Route::delete('delete/permiso/{id}', [
    'as' => 'delete.permiso',
    'uses' => 'PermisosAusenciasController@delete',
    'middleware' => 'permission:eliminar-permisosausencias'
]);
Route::get('aprobacion/permiso/{id}', [
    'as' => 'permiso.aprobacion',
    'uses' => 'PermisosAusenciasController@aprobacion',
    'middleware' => 'permission:aprobar-permisosausencias|aprobar-permisosausencias-inferior|anular-permisosausencias|anular-permisosausencias-inferior'

]);

/*Route::get('send/{id}/mail', [
    'as' => 'permiso.reenvio',
    'uses' => 'PermisosAusenciasController@reenvio',
    'middleware' => 'permission:reenviar-permisosausencias'
]);*/


//VACACIONES
Route::get('vacaciones', [
    'as' => 'vacaciones.list',
    'uses' => 'VacacionesController@index',
    'middleware' => 'permission:ver-vacaciones-todos|ver-vacaciones-oficina|ver-vacaciones-solo'
]);

Route::get('create/vacaciones', [
    'as' => 'create.vacaciones',
    'uses' => 'VacacionesController@create',
    'middleware' => 'permission:crear-vacaciones-todos|crear-vacaciones-oficina|crear-vacaciones-solo'    
]);

Route::post('store/vacaciones', [
    'as' => 'store.vacaciones',
    'uses' => 'VacacionesController@store',
    'middleware' => 'permission:crear-vacaciones-todos|crear-vacaciones-oficina|crear-vacaciones-solo|editar-vacaciones'    
]);

Route::get('edit/vacaciones/{id}', [
    'as' => 'edit.vacaciones',
    'uses' => 'VacacionesController@edit',
    'middleware' => 'permission:editar-vacaciones',    
]);

Route::delete('delete/vacaciones/{id}', [
    'as' => 'delete.vacaciones',
    'uses' => 'VacacionesController@delete',
    'middleware' => 'permission:eliminar-vacaciones'
]);
Route::get('aprobacion/vacaciones/{id}', [
    'as' => 'vacaciones.aprobacion',
    'uses' => 'VacacionesController@aprobacion',
    'middleware' => 'permission:aprobar-vacaciones|aprobar-vacaciones-inferior|anular-vacaciones|anular-vacaciones-inferior'
]);
//descargar solicitud vacaciones gasp
Route::get('descargar/vacaciones/{id}', [
    'as' => 'vacaciones.descargar',
    'uses' => 'VacacionesController@descargarSolicitudVacaciones',
    'middleware' => 'permission:descargar-vacaciones'
]);

////// VIAJES
Route::get('viajes', [
    'as' => 'viajes.list',
    'uses' => 'ViajesController@index',
    'middleware' => 'permission:ver-viajes-todos|ver-viajes-oficina|ver-viajes-solo'
]);

Route::get('create/viajes', [
    'as' => 'create.viajes',
    'uses' => 'ViajesController@create',
    'middleware' => 'permission:crear-viajes-todos|crear-viajes-oficina|crear-viajes-solo'    
]);

Route::post('store/viajes', [
    'as' => 'store.viajes',
    'uses' => 'ViajesController@store',
    'middleware' => 'permission:crear-viajes-todos|crear-viajes-oficina|crear-viajes-solo'    
]);

Route::get('edit/viajes/{id}', [
    'as' => 'edit.viajes',
    'uses' => 'ViajesController@edit',
    'middleware' => 'permission:editar-viajes'    
]);

Route::delete('delete/viajes/{id}', [
    'as' => 'delete.viajes',
    'uses' => 'ViajesController@delete',
    'middleware' => 'permission:eliminar-viajes'
]);

/*Route::get('/aprobacion/viajes/{id}', [
    'as' => 'aprobacion.viajes',
    'uses' => 'ViajesController@aprobacion',
    'middleware' => 'permission:aprobar-viajes'
]);*/


///// FERIADOS
Route::get('feriados', [
    'as' => 'feriados.list',
    'uses' => 'FeriadosController@index',
    'middleware' => 'permission:ver-feriados-todos|ver-feriados-pais'
]);

Route::get('feriados/calendar', [
    'as' => 'feriados.calendar',
    'uses' => 'FeriadosController@calendar',
    'middleware' => 'permission:ver-feriados-todos|ver-feriados-pais'
]);

Route::get('feriados/calendar/event', [
    'as' => 'feriados.calendar.event',
    'uses' => 'FeriadosController@event'
]);

Route::get('feriados/create', [
    'as' => 'feriados.create',
    'uses' => 'FeriadosController@create',
    'middleware' => 'permission:crear-feriado|crear-feriado-pais'
]);

Route::post('feriados/store', [
    'as' => 'feriados.store',
    'uses' => 'FeriadosController@store',
    'middleware' => 'permission:crear-feriado|crear-feriado-pais'
]);

Route::get('feriados/{id}/edit', [
    'as' => 'feriados.edit',
    'uses' => 'FeriadosController@edit',
    'middleware' => 'permission:editar-feriado'
]);

Route::put('feriados/{id}/update', [
    'as' => 'feriados.update',
    'uses' => 'FeriadosController@update',
    'middleware' => 'permission:editar-feriado'
]);

Route::delete('feriados/{id}/delete', [
    'as' => 'feriados.delete',
    'uses' => 'FeriadosController@destroy',
    'middleware' => 'permission:eliminar-feriado'
]);

Route::get('feriados/calendar/descargar', [
    'as' => 'feriados.calendar.descargar',
    'uses' => 'FeriadosController@export',
    'middleware' => 'permission:descargar-feriados'
]);

Route::get('feriados/actualizar', [
    'as' => 'feriados.actualizar',
    'uses' => 'FeriadosController@actualizarFeriados'
]);

///////INTEGRADOR
// Route::get('integrador/calendar', [
//     'as' => 'integrador.calendar',
//     'uses' => 'FeriadosController@calendarIntegral'
// ]);

// Route::get('integrador/calendar/event', [
//     'as' => 'integrador.calendar.event',
//     'uses' => 'FeriadosController@eventIntegral'
// ]);

/////CONTRATOS
Route::get('contratos', [
    'as' => 'contratos.list',
    'uses' => 'ContratosController@index',
    'middleware' => ['auth','permission:ver-contratos-todos|ver-contratos-oficina']
    
]);
Route::post('contratos/create', [
    'as' => 'contratos.create',
    'uses' => 'ContratosController@create',
    'middleware' => ['auth','permission:crear-contratos-todos|crear-contratos-oficina'],
]);

Route::post('contrato/store', [
    'as' => 'contrato.store',
    'uses' => 'ContratosController@store',
    'middleware' => ['auth','permission:crear-contratos-todos|crear-contratos-oficina'],
]);
Route::get('contrato/edit/{id}', [
    'as' => 'contrato.edit',
    'uses' => 'ContratosController@edit',
    'middleware' => ['auth','permission:editar-contratos'],
]);

Route::delete('contrato/delete/{id}', [
    'as' => 'contrato.delete',
    'uses' => 'ContratosController@delete',
    'middleware' => ['auth','permission:eliminar-contratos'],
]);

Route::delete('documento/delete/{id}', [
    'as' => 'documento.delete_documento',
    'uses' => 'ContratosController@delete_documento',
    'middleware' => ['auth','permission:editar-contratos'],
]);

Route::get('contrato/view/{id}', [
    'as' => 'contrato.view',
    'uses' => 'ContratosController@view',
    'middleware' => ['auth','permission:ver-contratos-todos|ver-contratos-oficina'],
]);

Route::get('/contrato/descargar/{id}', [
    'as' => 'contrato.pdf',
    'uses' => 'ContratosController@descargar_pdf',
    'middleware' => ['auth','permission:descargar-contratos']
]);

Route::get('/aprobacion/contrato/{id}', [
    'as' => 'contrato.aprobacion',
    'uses' => 'ContratosController@aprobacion',
    'middleware' => ['auth','permission:aprobar-contratos|confirmar-contratos']
]);

/*Route::get('contrato/email', [
    'as' => 'contrato.email',
    'uses' => 'ContratosController@email'
]);*/

Route::get('contrato/finalizacion', [
    'as' => 'contrato.finalizacion',
    'uses' => 'ContratosController@finalizacion',
    'middleware' => ['auth','role:Coordinadora|Directora|Admin']
]);

Route::get('contrato/{id}/anulacion', [
    'as' => 'contrato.anulacion',
    'uses' => 'ContratosController@anulacion',
    'middleware' => ['auth', 'permission:anular-contratos']
]);

/////ADENDA
Route::post('adenda/create', [
    'as' => 'adenda.create',
    'uses' => 'AdendaController@create',
    'middleware' => ['auth','permission:crear-adendas-contratos'],
]);

Route::get('adenda/list/{id}', [
    'as' => 'adenda.list',
    'uses' => 'AdendaController@ajax_list',
    'middleware' => ['auth','permission:ver-adendas-contratos'],
]);

Route::delete('adenda/delete/{id}', [
    'as' => 'contrato.delete',
    'uses' => 'AdendaController@delete',
    'middleware' => ['auth','permission:eliminar-adendas-contratos'],
]); 

//email

////RECEPCIONES

Route::get('recepciones', [
    'as' => 'recepciones.list',
    'uses' => 'RecepcionesController@index',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin|Contralora']
    
]);
Route::post('recepciones/create', [
    'as' => 'recepciones.create',
    'uses' => 'RecepcionesController@create',
    'middleware' => ['auth','role:Administradora'],
]);

Route::post('recepciones/store', [
    'as' => 'recepciones.store',
    'uses' => 'RecepcionesController@store',
    'middleware' => ['auth','role:Administradora'],
]);
Route::get('recepciones/{id}/edit', [
    'as' => 'recepciones.edit',
    'uses' => 'recepcionesController@edit',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin|Contralora'],
]);

Route::delete('recepciones/{id}/delete', [
    'as' => 'recepciones.delete',
    'uses' => 'recepcionesController@delete',
    'middleware' => ['auth','role:Administradora'],
]);

Route::get('recepciones/{id}/recogido', [
    'as' => 'recepciones.recogido',
    'uses' => 'recepcionesController@recogido',
    'middleware' => ['auth','role:Administradora'],
]);
Route::get('recepciones/{id}/email', [
    'as' => 'recepciones.email',
    'uses' => 'recepcionesController@email',
    'middleware' => ['auth','role:Administradora'],
]);
//------------------------

Route::get('cron/5a84dd2a32847', [    
    'uses' => 'CronController@contrato_email',   
]);

Route::get('cron/5a8f2abbcf526', [    
    'uses' => 'CronController@actualizarFeriados',   
]);

Route::post('email_prueba', [    
    'uses' => 'CronController@email_prueba',   
]);

Route::get('correlativo', [
   'as' => 'correlativo',
   'uses' => 'AjustesController@index',
   'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora|WebMaster'
]);

Route::get('create/correlativo',[
    'as' => 'create.correlativo',
    'uses' => 'AjustesController@create_correlativo'
]);


// BONO SALUD

Route::get('bonosalud', [
    'as' => 'bonosalud.index',
    'uses' => 'BonosSaludController@index',
    'middleware' => 'permission:ver-bonossalud-todos|ver-bonossalud-oficina|ver-bonossalud-solo'
]);

Route::get('bonosalud/create', [
    'as' => 'bonosalud.create',
    'uses' => 'BonosSaludController@create',
    'middleware' => 'permission:crear-bonossalud-todos|crear-bonossalud-oficina|crear-bonossalud-solo'
]);

Route::post('bonosalud', [
    'as' => 'bonosalud.store',
    'uses' => 'BonosSaludController@store',
    'middleware' => 'permission:crear-bonossalud-todos|crear-bonossalud-oficina|crear-bonossalud-solo'
]);

Route::get('bonosalud/{id}/edit', [
    'as' => 'bonosalud.edit',
    'uses' => 'BonosSaludController@edit',
    'middleware' => 'permission:editar-bonossalud'
]);

Route::post('bonosalud/{id}', [
    'as' => 'bonosalud.update',
    'uses' => 'BonosSaludController@update',
    'middleware' => 'permission:editar-bonossalud'
]);

Route::get('bonosalud/{id}/show', [
    'as' => 'bonosalud.show',
    'uses' => 'BonosSaludController@show',
    'middleware' => 'permission:ver-bonossalud-info'
]);

Route::get('bonosalud/{id}/aprobacion', [
    'as' => 'bonosalud.aprobacion',
    'uses' => 'BonosSaludController@aprobacion',
    'middleware' => 'permission:aprobar-bonossalud|aprobar-bonossalud-inferior|anular-bonossalud|anular-bonossalud-inferior'
]);

Route::get('bonosalud/{id}/download', [
    'as' => 'bonosalud.download',
    'uses' => 'BonosSaludController@download',
    'middleware' => 'permission:descargar-bonossalud'
]);

Route::get('bonosalud/{document}/downloadDocument', [
    'as' => 'bonosalud.downloadDocument',
    'uses' => 'BonosSaludController@downloadDocument',
    'middleware' => 'permission:editar-bonossalud|ver-bonossalud-info'
]);

Route::delete('bonosalud/{id}/delete', [
    'as' => 'bonosalud.delete',
    'uses' => 'BonosSaludController@destroy',
    'middleware' => 'permission:eliminar-bonossalud'
]);

Route::delete('bonosalud/{id}/deleteDoc', [
    'as' => 'bonosalud.deleteDoc',
    'uses' => 'BonosSaludController@deleteDoc',
    'middleware' => 'permission:editar-bonossalud',
]);

//COMPRAS

//ACTIVIDAD

Route::get('actividad', [
    'as' => 'actividad.index',
    'uses' => 'ActividadesController@index',
    'middleware' => 'permission:ver-actividad-todos|ver-actividad-oficina|ver-actividad-solo'
]);

Route::get('actividad/create', [
    'as' => 'actividad.create',
    'uses' => 'ActividadesController@create',
    'middleware' => 'permission:crear-actividad-todos|crear-actividad-oficina|crear-actividad-solo'
]);

Route::post('actividad/buscarMoneda', [
    'as' => 'actividad.buscarMoneda',
    'uses' => 'ActividadesController@buscarMoneda',
]);

Route::post('actividad', [
    'as' => 'actividad.store',
    'uses' => 'ActividadesController@store',
    'middleware' => 'permission:crear-actividad-todos|crear-actividad-oficina|crear-actividad-solo'
]);

Route::get('actividad/{id}/edit', [
    'as' => 'actividad.edit',
    'uses' => 'ActividadesController@edit',
    'middleware' => 'permission:editar-actividad'
]);

Route::post('actividad/{id}', [
    'as' => 'actividad.update',
    'uses' => 'ActividadesController@update',
    'middleware' => 'permission:editar-actividad'
]);

Route::get('actividad/{id}/show', [
    'as' => 'actividad.show',
    'uses' => 'ActividadesController@show',
    'middleware' => 'permission:ver-actividad-info'
]);

/*Route::post('actividad/aprobacion', [
    'as' => 'actividad.aprobacion',
    'uses' => 'ActividadesController@aprobacion',
    'middleware' => 'permission:aprobar-actividad-1|aprobar-actividad-2|aprobar-actividad-1-inferior|aprobar-actividad-2-inferior|anular-actividad-1|anular-actividad-1-inferior|anular-actividad-2|anular-actividad-2-inferior'
]);*/

Route::delete('actividad/{id}/delete', [
    'as' => 'actividad.delete',
    'uses' => 'ActividadesController@destroy',
    'middleware' => 'permission:eliminar-actividad'
]);

Route::delete('actividad/{id}/deleteDoc', [
    'as' => 'actividad.deleteDoc',
    'uses' => 'ActividadesController@deleteDoc',
    'middleware' => 'permission:editar-actividad',
]);

Route::get('actividad/{document}/downloadDocument', [
    'as' => 'actividad.downloadDocument',
    'uses' => 'ActividadesController@downloadDocument',
    'middleware' => 'permission:editar-actividad|ver-actividad-info'
]);

//DECISIONES

Route::get('decision', [
    'as' => 'decision.index',
    'uses' => 'DecisionesController@index',
    'middleware' => 'permission:ver-decision-todos|ver-decision-oficina|ver-decision-solo'
]);

Route::get('decision/{id}/create', [
    'as' => 'decision.create',
    'uses' => 'DecisionesController@create',
    'middleware' => 'permission:crear-decision-todos|crear-decision-oficina'
]);

Route::post('decision', [
    'as' => 'decision.store',
    'uses' => 'DecisionesController@store',
    'middleware' => 'permission:crear-decision-todos|crear-decision-oficina'
]);

Route::get('decision/{id}/edit', [
    'as' => 'decision.edit',
    'uses' => 'DecisionesController@edit',
    'middleware' => 'permission:editar-decision'
]);

Route::post('decision/{id}', [
    'as' => 'decision.update',
    'uses' => 'DecisionesController@update',
    'middleware' => 'permission:editar-decision'
]);

Route::get('decision/{id}/show', [
    'as' => 'decision.show',
    'uses' => 'DecisionesController@show',
    'middleware' => 'permission:ver-decision-info'
]);

/*Route::get('decision/{id}/aprobacion', [
    'as' => 'decision.aprobacion',
    'uses' => 'DecisionesController@aprobacion',
    'middleware' => 'permission:aprobar-decision-1|aprobar-decision-2|aprobar-decision-1-inferior|aprobar-decision-2-inferior|anular-decision-1|anular-decision-1-inferior|anular-decision-2|anular-decision-2-inferior'
]);*/

Route::delete('decision/{id}/delete', [
    'as' => 'decision.delete',
    'uses' => 'DecisionesController@destroy',
    'middleware' => 'permission:eliminar-decision'
]);

Route::delete('decision/{id}/deleteDoc', [
    'as' => 'decision.deleteDoc',
    'uses' => 'DecisionesController@deleteDoc',
    'middleware' => 'permission:editar-decision',
]);

Route::get('decision/{id}/download', [
    'as' => 'decision.download',
    'uses' => 'DecisionesController@download',
    'middleware' => 'permission:descargar-decision'
]);

Route::get('decision/{document}/downloadDocument', [
    'as' => 'decision.downloadDocument',
    'uses' => 'DecisionesController@downloadDocument',
    'middleware' => 'permission:editar-decision|ver-decision-info'
]);

//ORDEN DE COMPRA

Route::get('ordencompra', [
    'as' => 'ordencompra.index',
    'uses' => 'OrdenComprasController@index',
    'middleware' => 'permission:ver-ordencompra-todos|ver-ordencompra-oficina|ver-ordencompra-solo'
]);

Route::get('ordencompra/{id}/create', [
    'as' => 'ordencompra.create',
    'uses' => 'OrdenComprasController@create',
    'middleware' => 'permission:crear-ordencompra-todos|crear-ordencompra-oficina|crear-ordencompra-solo'
]);

Route::post('ordencompra', [
    'as' => 'ordencompra.store',
    'uses' => 'OrdenComprasController@store',
    'middleware' => 'permission:crear-ordencompra-todos|crear-ordencompra-oficina|crear-ordencompra-solo'
]);

Route::get('ordencompra/{id}/edit', [
    'as' => 'ordencompra.edit',
    'uses' => 'OrdenComprasController@edit',
    'middleware' => 'permission:editar-ordencompra'
]);

Route::post('ordencompra/{id}', [
    'as' => 'ordencompra.update',
    'uses' => 'OrdenComprasController@update',
    'middleware' => 'permission:editar-ordencompra'
]);

Route::get('ordencompra/{id}/show', [
    'as' => 'ordencompra.show',
    'uses' => 'OrdenComprasController@show',
    'middleware' => 'permission:ver-ordencompra-info'
]);

Route::get('ordencompra/{id}/aprobacion', [
    'as' => 'ordencompra.aprobacion',
    'uses' => 'OrdenComprasController@aprobacion',
    /*'middleware' => 'permission:aprobar-ordencompra-1|aprobar-ordencompra-2|aprobar-ordencompra-1-inferior|aprobar-ordencompra-2-inferior|anular-ordencompra-1|anular-ordencompra-1-inferior|anular-ordencompra-2|anular-ordencompra-2-inferior'*/
]);

Route::delete('ordencompra/{id}/delete', [
    'as' => 'ordencompra.delete',
    'uses' => 'OrdenComprasController@destroy',
    'middleware' => 'permission:eliminar-ordencompra'
]);

Route::get('ordencompra/{id}/download', [
    'as' => 'ordencompra.download',
    'uses' => 'OrdenComprasController@download',
    'middleware' => 'permission:descargar-ordencompra'
]);


//PAGOS

Route::get('pago', [
    'as' => 'pago.index',
    'uses' => 'PagosComprasController@index',
    'middleware' => 'permission:ver-pago-todos|ver-pago-oficina|ver-pago-solo'
]);

Route::get('pago/{id}/create', [
    'as' => 'pago.create',
    'uses' => 'PagosComprasController@create',
    'middleware' => 'permission:crear-pago-todos|crear-pago-oficina|crear-pago-solo'
]);

Route::post('pago', [
    'as' => 'pago.store',
    'uses' => 'PagosComprasController@store',
    'middleware' => 'permission:crear-pago-todos|crear-pago-oficina|crear-pago-solo'
]);

Route::get('pago/{id}/edit', [
    'as' => 'pago.edit',
    'uses' => 'PagosComprasController@edit',
    'middleware' => 'permission:editar-pago'
]);

Route::post('pago/{id}', [
    'as' => 'pago.update',
    'uses' => 'PagosComprasController@update',
    'middleware' => 'permission:editar-pago'
]);

Route::get('pago/{id}/show', [
    'as' => 'pago.show',
    'uses' => 'PagosComprasController@show',
    'middleware' => 'permission:ver-pago-info'
]);

Route::get('pago/{id}/aprobacion', [
    'as' => 'pago.aprobacion',
    'uses' => 'PagosComprasController@aprobacion',
    'middleware' => 'permission:aprobar-pago|aprobar-pago-inferior|anular-pago|anular-pago-inferior'
]);

Route::delete('pago/{id}/delete', [
    'as' => 'pago.delete',
    'uses' => 'PagosComprasController@destroy',
    'middleware' => 'permission:eliminar-pago'
]);

Route::get('pago/{id}/download', [
    'as' => 'pago.download',
    'uses' => 'PagosComprasController@download',
    'middleware' => 'permission:descargar-pago'
]);

//PROVEEDORES

Route::get('proveedor', [
    'as' => 'proveedor.index',
    'uses' => 'ProveedoresController@index',
    'middleware' => 'permission:ver-proveedor-todos|ver-proveedor-oficina'
]);

Route::get('proveedor/create', [
    'as' => 'proveedor.create',
    'uses' => 'ProveedoresController@create',
    'middleware' => 'permission:crear-proveedor-todos|crear-proveedor-oficina'
]);

Route::post('proveedor/buscarPais', [
    'as' => 'proveedor.buscarPais',
    'uses' => 'ProveedoresController@buscarPais',
    'middleware' => 'permission:crear-proveedor-todos'
]);

Route::post('proveedor', [
    'as' => 'proveedor.store',
    'uses' => 'ProveedoresController@store',
    'middleware' => 'permission:crear-proveedor-todos|crear-proveedor-oficina'
]);

Route::get('proveedor/{id}/edit', [
    'as' => 'proveedor.edit',
    'uses' => 'ProveedoresController@edit',
    'middleware' => 'permission:editar-proveedor'
]);

Route::post('proveedor/{id}', [
    'as' => 'proveedor.update',
    'uses' => 'ProveedoresController@update',
    'middleware' => 'permission:editar-proveedor'
]);

Route::get('proveedor/{id}/show', [
    'as' => 'proveedor.show',
    'uses' => 'ProveedoresController@show',
    'middleware' => 'permission:ver-proveedor-info'
]);

Route::delete('proveedor/{id}/delete', [
    'as' => 'proveedor.delete',
    'uses' => 'ProveedoresController@destroy',
    'middleware' => 'permission:eliminar-proveedor'
]);

//PROYECTOS

Route::get('proyecto', [
    'as' => 'proyecto.index',
    'uses' => 'ProyectosController@index',
    'middleware' => 'permission:ver-proyecto-todos|ver-proyecto-oficina'
]);

Route::get('proyecto/create', [
    'as' => 'proyecto.create',
    'uses' => 'ProyectosController@create',
    'middleware' => 'permission:crear-proyecto-todos|crear-proyecto-oficina'
]);

Route::post('proyecto', [
    'as' => 'proyecto.store',
    'uses' => 'ProyectosController@store',
    'middleware' => 'permission:crear-proyecto-todos|crear-proyecto-oficina'
]);

Route::get('proyecto/{id}/edit', [
    'as' => 'proyecto.edit',
    'uses' => 'ProyectosController@edit',
    'middleware' => 'permission:editar-proyecto'
]);

Route::post('proyecto/{id}', [
    'as' => 'proyecto.update',
    'uses' => 'ProyectosController@update',
    'middleware' => 'permission:editar-proyecto'
]);

Route::get('proyecto/{id}/show', [
    'as' => 'proyecto.show',
    'uses' => 'ProyectosController@show',
    'middleware' => 'permission:ver-proyecto-info'
]);

Route::delete('proyecto/{id}/delete', [
    'as' => 'proyecto.delete',
    'uses' => 'ProyectosController@destroy',
    'middleware' => 'permission:eliminar-proyecto'
]);


//CUENTAS

Route::get('cuenta', [
    'as' => 'cuenta.index',
    'uses' => 'CuentasController@index',
    'middleware' => 'permission:ver-cuenta-todos|ver-cuenta-oficina'
]);

Route::get('cuenta/create', [
    'as' => 'cuenta.create',
    'uses' => 'CuentasController@create',
    'middleware' => 'permission:crear-cuenta-todos|crear-cuenta-oficina'
]);

Route::post('cuenta', [
    'as' => 'cuenta.store',
    'uses' => 'CuentasController@store',
    'middleware' => 'permission:crear-cuenta-todos|crear-cuenta-oficina'
]);

Route::get('cuenta/{id}/edit', [
    'as' => 'cuenta.edit',
    'uses' => 'CuentasController@edit',
    'middleware' => 'permission:editar-cuenta'
]);

Route::post('cuenta/{id}', [
    'as' => 'cuenta.update',
    'uses' => 'CuentasController@update',
    'middleware' => 'permission:editar-cuenta'
]);

Route::get('cuenta/{id}/show', [
    'as' => 'cuenta.show',
    'uses' => 'CuentasController@show',
    'middleware' => 'permission:ver-cuenta-info'
]);

Route::delete('cuenta/{id}/delete', [
    'as' => 'cuenta.delete',
    'uses' => 'CuentasController@destroy',
    'middleware' => 'permission:eliminar-cuenta'
]);

//CENTROS DE COSTO

Route::get('centrocosto', [
    'as' => 'centrocosto.index',
    'uses' => 'CentrosCostoController@index',
    'middleware' => 'permission:ver-centrocosto-todos|ver-centrocosto-oficina'
]);

Route::get('centrocosto/create', [
    'as' => 'centrocosto.create',
    'uses' => 'CentrosCostoController@create',
    'middleware' => 'permission:crear-centrocosto-todos|crear-centrocosto-oficina'
]);

Route::post('centrocosto', [
    'as' => 'centrocosto.store',
    'uses' => 'CentrosCostoController@store',
    'middleware' => 'permission:crear-centrocosto-todos|crear-centrocosto-oficina'
]);

Route::get('centrocosto/{id}/edit', [
    'as' => 'centrocosto.edit',
    'uses' => 'CentrosCostoController@edit',
    'middleware' => 'permission:editar-centrocosto'
]);

Route::post('centrocosto/{id}', [
    'as' => 'centrocosto.update',
    'uses' => 'CentrosCostoController@update',
    'middleware' => 'permission:editar-centrocosto'
]);

Route::get('centrocosto/{id}/show', [
    'as' => 'centrocosto.show',
    'uses' => 'CentrosCostoController@show',
    'middleware' => 'permission:ver-centrocosto-info'
]);

Route::delete('centrocosto/{id}/delete', [
    'as' => 'centrocosto.delete',
    'uses' => 'CentrosCostoController@destroy',
    'middleware' => 'permission:eliminar-centrocosto'
]);

//LINEAS PRESUPUESTARIAS

Route::get('lineapresupuestaria', [
    'as' => 'lineapresupuestaria.index',
    'uses' => 'LineasPresupuestariasController@index',
    'middleware' => 'permission:ver-lineapresupuestaria-todos|ver-lineapresupuestaria-oficina'
]);

Route::get('lineapresupuestaria/create', [
    'as' => 'lineapresupuestaria.create',
    'uses' => 'LineasPresupuestariasController@create',
    'middleware' => 'permission:crear-lineapresupuestaria-todos|crear-lineapresupuestaria-oficina'
]);

Route::post('lineapresupuestaria', [
    'as' => 'lineapresupuestaria.store',
    'uses' => 'LineasPresupuestariasController@store',
    'middleware' => 'permission:crear-lineapresupuestaria-todos|crear-lineapresupuestaria-oficina'
]);

Route::get('lineapresupuestaria/{id}/edit', [
    'as' => 'lineapresupuestaria.edit',
    'uses' => 'LineasPresupuestariasController@edit',
    'middleware' => 'permission:editar-lineapresupuestaria'
]);

Route::post('lineapresupuestaria/{id}', [
    'as' => 'lineapresupuestaria.update',
    'uses' => 'LineasPresupuestariasController@update',
    'middleware' => 'permission:editar-lineapresupuestaria'
]);

Route::get('lineapresupuestaria/{id}/show', [
    'as' => 'lineapresupuestaria.show',
    'uses' => 'LineasPresupuestariasController@show',
    'middleware' => 'permission:ver-lineapresupuestaria-info'
]);

Route::delete('lineapresupuestaria/{id}/delete', [
    'as' => 'lineapresupuestaria.delete',
    'uses' => 'LineasPresupuestariasController@destroy',
    'middleware' => 'permission:eliminar-lineapresupuestaria'
]);