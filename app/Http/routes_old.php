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
    'uses' => 'ProfileController@sessions'
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
     'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::get('user/create', [
    'as' => 'user.create',
    'uses' => 'UsersController@create',
    'middleware' => 'role:Administradora|Admin|Coordinadora'
]);

Route::post('user/create', [
    'as' => 'user.store',
    'uses' => 'UsersController@store',
    'middleware' => 'role:Administradora|Admin|Coordinadora'
]);
Route::post('user/validacion', [
    'as' => 'user.validacion',
    'uses' => 'UsersController@validacion_ajax',    
]);
Route::get('user/{user}/show', [
    'as' => 'user.show',
    'uses' => 'UsersController@view',
    'middleware' => 'role:Administradora|Directora|Coordinadora|Contralora|Admin'
]);

Route::get('user/{user}/edit', [
    'as' => 'user.edit',
    'uses' => 'UsersController@edit',
     'middleware' => 'role:Administradora|Coordinadora|Admin|Directora|Contralora'
]);

Route::put('user/{user}/update/details', [
    'as' => 'user.update.details',
    'uses' => 'UsersController@updateDetails',
    'middleware' => 'role:Administradora|Coordinadora|Admin'
]);

Route::put('user/{user}/update/login-details', [
    'as' => 'user.update.login-details',
    'uses' => 'UsersController@updateLoginDetails'
]);

Route::delete('user/{user}/delete', [
    'as' => 'user.delete',
    'uses' => 'UsersController@delete'
]);

/*Route::post('user/{user}/update/avatar', [
    'as' => 'user.update.avatar',
    'uses' => 'UsersController@updateAvatar'
]);*/
Route::post('user/{user}/update/firma', [
    'as' => 'user.update.firma',
    'uses' => 'UsersController@updateFirma'
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
    'uses' => 'RolesController@index'
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
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::get('/crear/planilla', [
    'as' => 'planilla.crear',
    'uses' => 'PlanillaController@crear',
    'middleware' => 'role:Administradora|Coordinadora'
]);

Route::get('/edit/planilla/{id}', [
    'as' => 'planilla.edit',
    'uses' => 'PlanillaController@edit',
    'middleware' => 'role:Administradora|Directora|Contralora|Coordinadora|Admin'    

]);

Route::post('/store/planilla', [
    'as' => 'planilla.crear',
    'uses' => 'PlanillaController@store',
    'middleware' => 'role:Administradora|Coordinadora'

]);

Route::delete('/delete/planilla/{id}', [
    'as' => 'planilla.delete',
    'uses' => 'PlanillaController@delete',
    'middleware' => 'role:Administradora|Admin|Coordinadora'
]);

Route::get('/aprobacion/planilla/{id}', [
    'as' => 'planilla.aprobacion',
    'uses' => 'PlanillaController@aprobacion',
    'middleware' => 'role:Coordinadora|Directora|Admin|Contralora'
]);

Route::get('/descargar/planilla/{id}', [
    'as' => 'planilla.descargar',
    'uses' => 'PlanillaController@descargar_planilla',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('/seguro_social/planilla', [
    'as' => 'seguro.planilla',
    'uses' => 'PlanillaController@seguroSocial',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

///// REPORTES

Route::get('/reportes', [
    'as' => 'reportes',
    'uses' => 'ReportesController@index',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('/reporte/planillas', [
    'as' => 'reporte.planillas',
    'uses' => 'ReportesController@reporte_planillas',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('/reporte/empleado', [
    'as' => 'reporte.empleado',
    'uses' => 'ReportesController@boleta_empleados',
    'middleware' => 'role:Administradora|Coordinadora|Direcotra|Contralora|Admin'
]);

Route::post('/reporte/vacaciones_permisos', [
    'as' => 'reporte.vacaciones_permisos',
    'uses' => 'ReportesController@reportes_vacaciones_permisos',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('/reporte/liquidacion', [
    'as' => 'reporte.liquidacion',
    'uses' => 'ReportesController@boleta_liquidacion',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::get('/reporte/ajax/liquidacion', [
    'as' => 'reporte.ajax.liquidacion',
    'uses' => 'ReportesController@ajax_liquidacion',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('/reporte/contratos', [
    'as' => 'reporte.contratos',
    'uses' => 'ReportesController@contratos',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin|Contralora'
]);

Route::post('reporte/calendar/descargar', [
    'as' => 'reporte.calendar.descargar',
    'uses' => 'ReportesController@export_integrador',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin'
]);



///// AJUSTES
Route::get('ajustes', [
    'as' => 'ajustes',
    'uses' => 'AjustesController@index',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::post('ajustes', [
    'as' => 'ajustes',
    'uses' => 'AjustesController@store',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::delete('delete/cargo/{id}', [
    'as' => 'delete.cargo',
    'uses' => 'AjustesController@delete_cargo',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::delete('delete/profesion/{id}', [
    'as' => 'delete.profesion',
    'uses' => 'AjustesController@delete_profesion',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::delete('delete/motivo/{id}', [
    'as' => 'delete.motivo',
    'uses' => 'AjustesController@delete_motivo',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::get('create/cargo_profesion', [
    'as' => 'create.cargo_profesion',
    'uses' => 'AjustesController@create_cargo_profesion',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);
Route::get('create/motivo_permiso', [
    'as' => 'create.motivo_permiso',
    'uses' => 'AjustesController@create_cargo_profesion',
    'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);




///// PERMISOS
Route::get('permisos', [
    'as' => 'permisos.list',
    'uses' => 'PermisosAusenciasController@index'
]);

Route::get('create/permiso', [
    'as' => 'create.permiso',
    'uses' => 'PermisosAusenciasController@create',    
]);

Route::get('edit/permiso/{id}', [
    'as' => 'edit.permiso',
    'uses' => 'PermisosAusenciasController@edit',
    'middleware' => 'role:Administradora|Coordinadora|Colega|Directora|Contralora'
]);

Route::post('store/permiso', [
    'as' => 'store.permiso',
    'uses' => 'PermisosAusenciasController@store',
    'middleware' => 'role:Administradora|Coordinadora|Colega|Directora|Contralora|Admin'
]);

Route::delete('delete/permiso/{id}', [
    'as' => 'delete.permiso',
    'uses' => 'PermisosAusenciasController@delete',
    'middleware' => 'role:Administradora|Coordinadora|Colega|Directora|Contralora|Admin'
]);
Route::get('aprobacion/permiso/{id}', [
    'as' => 'permiso.aprobacion',
    'uses' => 'PermisosAusenciasController@aprobacion',
    'middleware' => 'role:Coordinadora'

]);

Route::get('send/{id}/mail', [
    'as' => 'permiso.reenvio',
    'uses' => 'PermisosAusenciasController@reenvio',
    'middleware' => 'role:Administradora|Coordinadora'
]);


//VACACIONES
Route::get('vacaciones', [
    'as' => 'vacaciones.list',
    'uses' => 'VacacionesController@index'
]);

Route::get('create/vacaciones', [
    'as' => 'create.vacaciones',
    'uses' => 'VacacionesController@create',    
]);

Route::post('store/vacaciones', [
    'as' => 'store.vacaciones',
    'uses' => 'VacacionesController@store',    
]);

Route::get('edit/vacaciones/{id}', [
    'as' => 'edit.vacaciones',
    'uses' => 'VacacionesController@edit'    
]);

Route::delete('delete/vacaciones/{id}', [
    'as' => 'delete.vacaciones',
    'uses' => 'VacacionesController@delete',
    'middleware' => 'role:Administradora|Colega|Coordinadora'
]);
Route::get('aprobacion/vacaciones/{id}', [
    'as' => 'vacaciones.aprobacion',
    'uses' => 'VacacionesController@aprobacion',
    'middleware' => 'role:Directora|Contralora'
]);

////// VIAJES
Route::get('viajes', [
    'as' => 'viajes.list',
    'uses' => 'ViajesController@index'
]);

Route::get('create/viajes', [
    'as' => 'create.viajes',
    'uses' => 'ViajesController@create',    
]);

Route::post('store/viajes', [
    'as' => 'store.viajes',
    'uses' => 'ViajesController@store',    
]);

Route::get('edit/viajes/{id}', [
    'as' => 'edit.viajes',
    'uses' => 'ViajesController@edit'    
]);

Route::delete('delete/viajes/{id}', [
    'as' => 'delete.viajes',
    'uses' => 'ViajesController@delete',
    'middleware' => 'role:Administradora|Colega|Coordinadora|Admin'
]);


///// FERIADOS
Route::get('feriados', [
    'as' => 'feriados.list',
    'uses' => 'FeriadosController@index'
]);

Route::get('feriados/calendar', [
    'as' => 'feriados.calendar',
    'uses' => 'FeriadosController@calendar'
]);

Route::get('feriados/calendar/event', [
    'as' => 'feriados.calendar.event',
    'uses' => 'FeriadosController@event'
]);

Route::get('feriados/create', [
    'as' => 'feriados.create',
    'uses' => 'FeriadosController@create'
]);

Route::post('feriados/store', [
    'as' => 'feriados.store',
    'uses' => 'FeriadosController@store'
]);

Route::get('feriados/{id}/edit', [
    'as' => 'feriados.edit',
    'uses' => 'FeriadosController@edit'
]);

Route::put('feriados/{id}/update', [
    'as' => 'feriados.update',
    'uses' => 'FeriadosController@update'
]);

Route::delete('feriados/{id}/delete', [
    'as' => 'feriados.delete',
    'uses' => 'FeriadosController@destroy'
]);

Route::get('feriados/calendar/descargar', [
    'as' => 'feriados.calendar.descargar',
    'uses' => 'FeriadosController@export',
    'middleware' => 'role:Administradora|Coordinadora|Directora|Admin'
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
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin|Contralora']
    
]);
Route::post('contratos/create', [
    'as' => 'contratos.create',
    'uses' => 'ContratosController@create',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin'],
]);

Route::post('contrato/store', [
    'as' => 'contrato.store',
    'uses' => 'ContratosController@store',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin'],
]);
Route::get('contrato/edit/{id}', [
    'as' => 'contrato.edit',
    'uses' => 'ContratosController@edit',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin'],
]);

Route::delete('contrato/delete/{id}', [
    'as' => 'contrato.delete',
    'uses' => 'ContratosController@delete',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin'],
]);

Route::delete('documento/delete/{id}', [
    'as' => 'documento.delete_documento',
    'uses' => 'ContratosController@delete_documento',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin'],
]);

Route::get('contrato/view/{id}', [
    'as' => 'contrato.view',
    'uses' => 'ContratosController@view',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin|Contralora'],
]);

Route::get('/contrato/descargar/{id}', [
    'as' => 'contrato.pdf',
    'uses' => 'ContratosController@descargar_pdf',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin|Contralora']
]);

Route::get('/aprobacion/contrato/{id}', [
    'as' => 'contrato.aprobacion',
    'uses' => 'ContratosController@aprobacion',
    'middleware' => ['auth','role:Administradora|Coordinadora|Directora|Admin']
]);

Route::get('contrato/email', [
    'as' => 'contrato.email',
    'uses' => 'ContratosController@email'
]);

Route::get('contrato/finalizacion', [
    'as' => 'contrato.finalizacion',
    'uses' => 'ContratosController@finalizacion',
    'middleware' => ['auth','role:Coordinadora|Directora|Admin']
]);

/////ADENDA
Route::post('adenda/create', [
    'as' => 'adenda.create',
    'uses' => 'AdendaController@create',
    'middleware' => ['auth','role:Coordinadora|Directora|Admin'],
]);

Route::get('adenda/list/{id}', [
    'as' => 'adenda.list',
    'uses' => 'AdendaController@ajax_list',
    'middleware' => ['auth','role:Coordinadora|Directora|Admin'],
]);

Route::delete('adenda/delete/{id}', [
    'as' => 'contrato.delete',
    'uses' => 'AdendaController@delete',
    'middleware' => ['auth','role:Coordinadora|Directora|Admin'],
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
   'middleware' => 'role:Administradora|Coordinadora|Admin|Contralora'
]);

Route::get('create/correlativo',[
    'as' => 'create.correlativo',
    'uses' => 'AjustesController@create_correlativo'
]);
