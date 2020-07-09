import setupCopy from './admin_clipboard';

window.App = {
	menu: require('./admin_menu'),
	auth: require('./admin_auth'),
	common: require('./admin_common'),

	components: {
		admin_settings: require('./admin_settings'),
		admin_payment: require('./admin_payment'),
		admin_tests: require('./admin_exams'),
		admin_authors_upload: require('./admin_authors_upload'),
	}

}

App.auth.init();
App.common.init();
setupCopy();
