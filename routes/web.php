<?php
    Route::get('/', function(){
        return view ('welcome');
    });

    Route::post('check-unique',['as'=>'check-unique','uses'=>'Tools@postCheckUnique']);
	Route::post('change-language',['as'=>'change-language','uses'=>'Tools@postChangeLanguage']);
/**
 * ======================================================================================================
 * 					Admin routing start from here
 * ======================================================================================================
 */
	Route::get(ADMIN_PATH,['as'=>'admin','uses'=>'admin\Authentication@getLogin']);
	Route::get(ADMIN_PATH.'/login',['as'=>'admin-login','uses'=>'admin\Authentication@getLogin']);
	Route::post(ADMIN_PATH.'/login',['as'=>'admin-post-login','uses'=>'admin\Authentication@postLogin']);
	Route::get(ADMIN_PATH.'/forgot-password',['as'=>'admin-get-forgot-password','uses'=>'admin\Authentication@getForgotPassword']);
	Route::post(ADMIN_PATH.'/forgot-password',['as'=>'admin-post-forgot-password','uses'=>'admin\Authentication@postForgotPassword']);

	Route::get(ADMIN_PATH.'/reset-password/{unique_code}',['as'=>'admin-get-reset-password','uses'=>'admin\Authentication@getResetPassword']);
	Route::post(ADMIN_PATH.'/reset-password',['as'=>'admin-post-reset-password','uses'=>'admin\Authentication@postResetPassword']);

	Route::group(['prefix'=> ADMIN_PATH,'as' => 'admin-','middleware'=>'admin.auth','lang','namespace'=>'admin'],function(){

		Route::get('dashboard',['as'=>'dashboard','uses'=>'Authentication@index']);
		Route::get('admin-coming-soon',['as'=>'coming-soon','uses'=>'Authentication@getComingSoon']);
		Route::get('admin-profile-settings',['as'=>'profile-settings','uses'=>'Authentication@getProfileSettings']);
		Route::post('admin-profile-settings',['as'=>'profile-settings','uses'=>'Authentication@postProfileSettings']);
		Route::post('admin-logout',['as'=>'logout','uses'=>'Authentication@postLogout']);
        Route::get('unread-tickets',['as'=>'unread-tickets','uses'=>'SupportTicket/MyTicketCrud@getUnreadSupportTtickets']);


	    Route::group(['prefix'=> 'content-management','as' => 'content-management-','middleware'=>'admin.auth:content-management'],function(){
	        Route::group(['as' => 'cms-page-','prefix' => 'cms_page'],function(){
				Route::get('/',['uses'=>'CmsPageCrud@show','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('list',['as'=>'list','uses'=>'CmsPageCrud@show','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('view/{id?}',['as'=>'view','uses'=>'CmsPageCrud@view','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('add',['as'=>'add','uses'=>'CmsPageCrud@add','middleware' => 'admin.auth:cms-page-@canAdd']);
      			Route::post('insert',['as'=>'insert','uses'=>'CmsPageCrud@insert','middleware' => 'admin.auth:cms-page-@canAdd']);
      			Route::get('edit/{id?}',['as'=>'edit','uses'=>'CmsPageCrud@edit','middleware' => 'admin.auth:cms-page-@canModify']);
      			Route::post('update',['as'=>'update','uses'=>'CmsPageCrud@update','middleware' => 'admin.auth:cms-page-@canModify']);
      			Route::get('delete/{id?}',['as'=>'delete','uses'=>'CmsPageCrud@delete','middleware' => 'admin.auth:cms-page-@canModify']);
	      	});
	    });

	    Route::group(['prefix'=> 'settings','as' => 'settings-','middleware'=>'admin.auth:settings'],function(){

			Route::get('role-management',['as'=>'role-management','uses'=>'RoleManagement@listRoleManagement','middleware' => 'admin.auth:role-management@canView']);
	        Route::post('role-management',['as'=>'role-management','uses'=>'RoleManagement@postRoleManagement','middleware' => 'admin.auth:role-management@canAdd']);
	        Route::get('role-permission-management',['as'=>'role-permission-management','uses'=>'RolePermissionManagement@listRolePermissionManagement','middleware' => 'admin.auth:role-permission-management@canView']);
	        Route::post('role-permission-management',['as'=>'role-permission-management','uses'=>'RolePermissionManagement@postRolePermissionManagement','middleware' => 'admin.auth:role-permission-management@canModify']);

			Route::group(['as' => 'user-group-','prefix' => 'user-group','middleware'=>'admin.auth:user-group-'],function(){
				Route::get('/',['uses'=>'RoleCrud@show','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('list',['as'=>'list','uses'=>'RoleCrud@show','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('view/{id?}',['as'=>'view','uses'=>'RoleCrud@view','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('add',['as'=>'add','uses'=>'RoleCrud@add','middleware' => 'admin.auth:user-group-@canAdd']);
				Route::post('insert',['as'=>'insert','uses'=>'RoleCrud@insert','middleware' => 'admin.auth:user-group-@canAdd']);
				Route::get('edit/{id?}',['as'=>'edit','uses'=>'RoleCrud@edit','middleware' => 'admin.auth:user-group-@canModify']);
				Route::post('update',['as'=>'update','uses'=>'RoleCrud@update','middleware' => 'admin.auth:user-group-@canModify']);
				Route::get('delete/{id?}',['as'=>'delete','uses'=>'RoleCrud@delete','middleware' => 'admin.auth:user-group-@canModify']);
			});
	        Route::group(['as' => 'site-settings-','prefix' => 'site_setting'],function(){
	            Route::get('/',['uses'=>'SiteSettingCrud@show','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('list',['as'=>'list','uses'=>'SiteSettingCrud@show','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('view/{id?}',['as'=>'view','uses'=>'SiteSettingCrud@view','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('add',['as'=>'add','uses'=>'SiteSettingCrud@add','middleware' => 'admin.auth:site-settings-@canAdd']);
	            Route::post('insert',['as'=>'insert','uses'=>'SiteSettingCrud@insert','middleware' => 'admin.auth:site-settings-@canAdd']);
	            Route::get('edit/{id?}',['as'=>'edit','uses'=>'SiteSettingCrud@edit','middleware' => 'admin.auth:site-settings-@canModify']);
	            Route::post('update',['as'=>'update','uses'=>'SiteSettingCrud@update','middleware' => 'admin.auth:site-settings-@canModify']);
	            Route::get('delete/{id?}',['as'=>'delete','uses'=>'SiteSettingCrud@delete','middleware' => 'admin.auth:site-settings-@canModify']);
	        });
		  	Route::group(['as' => 'module-management-','prefix' => 'module','middleware' => 'admin.auth:module-management-@canView'],function(){
				Route::get('/',['uses'=>'ModuleCrud@show','middleware' => 'admin.auth:module-management-@canView']);
				Route::get('list',['as'=>'list','uses'=>'ModuleCrud@show', 'middleware' => 'admin.auth:module-management-@canView']);
				Route::get('view/{id?}',['as'=>'view','uses'=>'ModuleCrud@view', 'middleware' => 'admin.auth:module-management-@canView']);
				Route::get('add',['as'=>'add','uses'=>'ModuleCrud@add', 'middleware' => 'admin.auth:module-management-@canAdd']);
				Route::post('insert',['as'=>'insert','uses'=>'ModuleCrud@insert', 'middleware' => 'admin.auth:module-management-@canAdd']);
				Route::get('edit/{id?}',['as'=>'edit','uses'=>'ModuleCrud@edit', 'middleware' => 'admin.auth:module-management-@canModify']);
				Route::post('update',['as'=>'update','uses'=>'ModuleCrud@update', 'middleware' => 'admin.auth:module-management-@canModify']);
				Route::get('delete/{id?}',['as'=>'delete','uses'=>'ModuleCrud@delete', 'middleware' => 'admin.auth:module-management-@canModify']);
			});
	        Route::group(['as' => 'mail-template-','prefix' => 'mail_template','middleware' => 'admin.auth:mail-template-@canView'],function(){
	            Route::get('/',['uses'=>'MailTemplateCrud@show','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('list',['as'=>'list','uses'=>'MailTemplateCrud@show','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('view/{id?}',['as'=>'view','uses'=>'MailTemplateCrud@view','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('add',['as'=>'add','uses'=>'MailTemplateCrud@add','middleware' => 'admin.auth:mail-template-@canAdd']);
	            Route::post('insert',['as'=>'insert','uses'=>'MailTemplateCrud@insert','middleware' => 'admin.auth:mail-template-@canAdd']);
	            Route::get('edit/{id?}',['as'=>'edit','uses'=>'MailTemplateCrud@edit','middleware' => 'admin.auth:mail-template-@canModify']);
	            Route::post('update',['as'=>'update','uses'=>'MailTemplateCrud@update','middleware' => 'admin.auth:mail-template-@canModify']);
	            Route::get('delete/{id?}',['as'=>'delete','uses'=>'MailTemplateCrud@delete','middleware' => 'admin.auth:mail-template-@canModify']);
            });
	    });
		Route::group(['as' => 'users-','prefix' => 'user','middleware' => ['admin.auth:users-@canView', 'OnlyParent', 'Exist:App\Models\User']],function(){
			Route::get('/',['uses'=>'UserCrud@show','middleware' => 'admin.auth:users-@canView']);
			Route::get('list/{id?}',['as'=>'list','uses'=>'UserCrud@show','middleware' => 'admin.auth:users-@canView']);
			Route::get('data/{id?}',['as'=>'data','uses'=>'UserCrud@data','middleware' => 'admin.auth:users-@canView']);
			Route::get('view/{id?}',['as'=>'view','uses'=>'UserCrud@view','middleware' => 'admin.auth:users-@canView']);
			Route::get('add/{id?}',['as'=>'add','uses'=>'UserCrud@add','middleware' => 'admin.auth:users-@canAdd']);
			Route::post('insert',['as'=>'insert','uses'=>'UserCrud@insert','middleware' => 'admin.auth:users-@canAdd']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'UserCrud@edit','middleware' => 'admin.auth:users-@canModify']);
			Route::post('update',['as'=>'update','uses'=>'UserCrud@update','middleware' => 'admin.auth:users-@canModify']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'UserCrud@delete','middleware' => 'admin.auth:users-@canModify']);

            Route::get('/get-username/{slug?}',['as'=>'get-username', 'uses'=>'UserCrud@getUserName']);
            Route::get('/get-all-parents/{id?}',['as'=>'get-all-parents', 'uses'=>'UserCrud@getAllParentList']);
        });
        Route::group(['as' => 'user-activity-log-','prefix' => 'users-activity-log', 'middleware' => ['admin.auth:user-activity-log-@canView','Exist:App\Models\User,user_id']],function(){
			Route::get('list/{user_id?}',['as'=>'list','uses'=>'UsersActivityLogCrud@show','middleware' => 'admin.auth:user-activity-log-@canView']);
            Route::get('data/{id?}',['as'=>'data','uses'=>'UsersActivityLogCrud@data','middleware' => 'admin.auth:user-activity-log-@canView']);
            Route::get('view/{id?}',['as'=>'view','uses'=>'UsersActivityLogCrud@view']);
			Route::get('add',['as'=>'add','uses'=>'UsersActivityLogCrud@add']);
			Route::post('insert',['as'=>'insert','uses'=>'UsersActivityLogCrud@insert']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'UsersActivityLogCrud@edit']);
			Route::post('update',['as'=>'update','uses'=>'UsersActivityLogCrud@update']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'UsersActivityLogCrud@delete']);
            Route::get('/{user_id?}',['uses'=>'UsersActivityLogCrud@show','middleware' => 'admin.auth:user-activity-log-@canView']);
		});
        Route::group(['as' => 'tests-','prefix' => 'tests', 'middleware' => ['admin.auth:tests-@canView','Exist:App\Models\PathTest']],function(){
			Route::get('',['uses'=>'PathTestCrud@show']);
			Route::get('list',['as'=>'list','uses'=>'PathTestCrud@show']);
			Route::get('view/{id?}',['as'=>'view','uses'=>'PathTestCrud@view']);
			Route::get('add',['as'=>'add','uses'=>'PathTestCrud@add','middleware' => 'admin.auth:tests-@canAdd']);
			Route::post('insert',['as'=>'insert','uses'=>'PathTestCrud@insert','middleware' => 'admin.auth:tests-@canAdd']);
            Route::get('upload-csv',['as'=>'get-upload-csv-form','uses'=>'PathTestCrud@getUploadCsvForm','middleware' => 'admin.auth:tests-@canAdd']);
            Route::post('upload-csv',['as'=>'upload-csv','uses'=>'PathTestCrud@uploadCsv','middleware' => 'admin.auth:tests-@canAdd']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'PathTestCrud@edit','middleware' => 'admin.auth:tests-@canModify']);
			Route::post('update',['as'=>'update','uses'=>'PathTestCrud@update','middleware' => 'admin.auth:tests-@canModify']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'PathTestCrud@delete','middleware' => 'admin.auth:tests-@canModify']);
		});
        Route::group(['as' => 'collectors-','prefix' => 'collectors', 'middleware' => ['admin.auth:collectors-@canView','Exist:App\Models\Collector']],function(){
            Route::get('',['uses'=>'CollectorCrud@show']);
            Route::get('list',['as'=>'list','uses'=>'CollectorCrud@show']);
            Route::get('view/{id?}',['as'=>'view','uses'=>'CollectorCrud@view']);
            Route::get('add',['as'=>'add','uses'=>'CollectorCrud@add','middleware' => 'admin.auth:collectors-@canAdd']);
            Route::post('insert',['as'=>'insert','uses'=>'CollectorCrud@insert','middleware' => 'admin.auth:collectors-@canAdd']);
            Route::get('upload-csv',['as'=>'get-upload-csv-form','uses'=>'CollectorCrud@getUploadCsvForm','middleware' => 'admin.auth:collectors-@canAdd']);
            Route::post('upload-csv',['as'=>'upload-csv','uses'=>'CollectorCrud@uploadCsv','middleware' => 'admin.auth:collectors-@canAdd']);
            Route::get('edit/{id?}',['as'=>'edit','uses'=>'CollectorCrud@edit','middleware' => 'admin.auth:collectors-@canModify']);
            Route::post('update',['as'=>'update','uses'=>'CollectorCrud@update','middleware' => 'admin.auth:collectors-@canModify']);
            Route::get('delete/{id?}',['as'=>'delete','uses'=>'CollectorCrud@delete','middleware' => 'admin.auth:collectors-@canModify']);

            Route::group(['as' => 'commission-','prefix' => 'commission', 'middleware' => ['admin.auth:collectors-commission-@canView','Exist:App\Models\CollectorsPathTest,test_id']],function(){
    			Route::get('/{id?}',['uses'=>'CollectorsPathTestCrud@show']);
    			Route::get('list/{id?}',['as'=>'list','uses'=>'CollectorsPathTestCrud@show']);
    			Route::get('view/{id?}/{test_id?}',['as'=>'view','uses'=>'CollectorsPathTestCrud@view']);
    			Route::get('add/{id?}',['as'=>'add','uses'=>'CollectorsPathTestCrud@massAdd','middleware' => 'admin.auth:collectors-commission-@canAdd']);
    			Route::post('insert/{id?}',['as'=>'insert','uses'=>'CollectorsPathTestCrud@massInsert','middleware' => 'admin.auth:collectors-commission-@canAdd']);
                Route::get('upload-csv/{id?}',['as'=>'get-upload-csv-form','uses'=>'CollectorsPathTestCrud@getUploadCsvForm','middleware' => 'admin.auth:collectors-commission-@canAdd']);
                Route::post('upload-csv/{id?}',['as'=>'upload-csv','uses'=>'CollectorsPathTestCrud@uploadCsv','middleware' => 'admin.auth:collectors-commission-@canAdd']);
                Route::get('edit/{id?}/{test_id?}',['as'=>'edit','uses'=>'CollectorsPathTestCrud@edit','middleware' => 'admin.auth:collectors-commission-@canModify']);
    			Route::post('update/{id?}',['as'=>'update','uses'=>'CollectorsPathTestCrud@update','middleware' => 'admin.auth:collectors-commission-@canModify']);
    			Route::get('delete/{id?}/{test_id?}',['as'=>'delete','uses'=>'CollectorsPathTestCrud@delete','middleware' => 'admin.auth:collectors-commission-@canModify']);
    		});
        });
        Route::group(['as' => 'billing-','prefix' => 'billing', 'middleware' => ['admin.auth:billing-@canView','Exist:App\Models\Billing']],function(){
			Route::get('',['uses'=>'BillingController@show']);
			Route::get('list',['as'=>'list','uses'=>'BillingController@show']);
			Route::get('data',['as'=>'data','uses'=>'BillingController@data']);
			Route::get('view/{id?}',['as'=>'view','uses'=>'BillingController@view']);
			Route::get('add',['as'=>'add','uses'=>'BillingController@add','middleware' => 'admin.auth:billing-@canAdd']);
			Route::post('insert',['as'=>'insert','uses'=>'BillingController@insert','middleware' => 'admin.auth:billing-@canAdd']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'BillingController@edit','middleware' => 'admin.auth:billing-@canModify']);
			Route::post('update',['as'=>'update','uses'=>'BillingController@update','middleware' => 'admin.auth:billing-@canModify']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'BillingController@delete','middleware' => 'admin.auth:billing-@canModify']);
			Route::get('get-patient-suggestion/{slug?}',['as'=>'get-patient-suggestion','uses'=>'BillingController@getPatientName','middleware' => 'admin.auth:billing-@canAdd']);
			Route::get('get-billing-amount',['as'=>'get-billing-amount','uses'=>'BillingController@getBillingAmount','middleware' => 'admin.auth:billing-@canAdd']);

            Route::group(['as' => 'payments-','prefix' => 'payments', 'middleware' => ['admin.auth:payments-@canView','Exist:App\Models\Payment,payment_id']],function(){
    			Route::get('/{id?}',['uses'=>'PaymentCrud@show']);
    			Route::get('list/{id?}',['as'=>'list','uses'=>'PaymentCrud@show']);
    			Route::get('view/{id?}/{payment_id?}',['as'=>'view','uses'=>'PaymentCrud@view']);
    			Route::get('add/{id?}',['as'=>'add','uses'=>'PaymentCrud@add','middleware' => 'admin.auth:payments-@canAdd']);
    			Route::post('insert/{id?}',['as'=>'insert','uses'=>'PaymentCrud@insert','middleware' => 'admin.auth:payments-@canAdd']);
    			Route::get('edit/{id?}/{payment_id?}',['as'=>'edit','uses'=>'PaymentCrud@edit','middleware' => 'admin.auth:payments-@canModify']);
    			Route::post('update/{id?}',['as'=>'update','uses'=>'PaymentCrud@update','middleware' => 'admin.auth:payments-@canModify']);
    			Route::get('delete/{id?}/{payment_id?}',['as'=>'delete','uses'=>'PaymentCrud@delete','middleware' => 'admin.auth:payments-@canModify']);
    		});
        });
        Route::group(['as' => 'financial-report-','prefix' => 'financial-report', 'middleware' => ['admin.auth:financial-report-@canView']],function(){
            Route::get('',['uses'=>'FinalcialReportController@show']);
            Route::get('generate',['as' => 'generate', 'uses'=>'FinalcialReportController@generateReport']);
        });
    });
