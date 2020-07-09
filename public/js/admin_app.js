/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendGet", function() { return sendGet; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendPost", function() { return sendPost; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });


var sendGet = function sendGet(url, params) {
  return new Promise(function (resolve, reject) {
    console.log('reqie');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: url,
      data: params,
      success: function success(data) {
        return resolve(data);
      },
      // error: reject(e.responseJSON, e.status, e),
      error: function error(e) {
        return reject(e);
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  });
};

var sendPost = function sendPost(url, params) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: url,
      data: params,
      success: function success(data) {
        return resolve(data);
      },
      error: function error(e) {
        return reject(e.responseJSON, e.status);
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  });
};

var init = function init() {

  $.fn.commonForm = function (success, error, options) {
    return this.each(function () {
      var $form = $(this);

      var beforeSubmit = function beforeSubmit() {
        $form.find('.error').addClass('hidden');
      };

      var successHandler = typeof success === 'function' ? function (data) {
        return success(data, $form);
      } : function (data) {
        if (typeof success === 'string') {
          var $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
          $success.find('span').text(success);
          var $alertArea = $('.alert-area');

          if ($alertArea.length > 1) {
            console.log('isolatin');
            var $parent = $form;
            while ($parent.prop('tagName').toLowerCase() !== 'body') {
              $alertArea = $parent.find('.alert-area');
              if ($alertArea.length) {
                $alertArea = $alertArea.eq(0);
                break;
              }

              $parent = $parent.parent();
            }
          }

          if ($alertArea.length) {
            $alertArea.append($success);
            setTimeout(function () {
              return $success.fadeOut(500, function () {
                return $success.remove();
              });
            }, 10000);
          }
        }
      };

      var errorHandler = typeof error === 'function' ? function (e) {
        return error(e.responseJSON, e.status);
      } : function (e) {
        var data = e.responseJSON;

        if (data && typeof data.redirect != 'undefined') {
          document.location.href = data.redirect;
          return;
        }

        if (data && typeof data.errors !== 'undefined') {
          var firstErrorField = false;

          Object.keys(data.errors).map(function (key) {
            var $field = $form.find('[name="' + key + '"]');
            var $group = $field.closest('.form-group,.quasi-form-group');
            var $error = $group.find('.error');
            if ($error.length === 0) {
              $error = $('<label class="help-inline help-small no-left-padding hidden error"></label>');

              if ($group.is('.quasi-form-group')) {
                $group.append($error);
              } else {
                $group.find('div:last').append($error);
              }
            }

            console.log('ero: ' + $error.length + ' - ' + data.errors[key]);

            $error.removeClass('hidden').html(data.errors[key]);
            if (firstErrorField === false) firstErrorField = $field;
          });

          if (firstErrorField) {
            firstErrorField.focus();
          }
        }
      };

      $form.ajaxForm({
        beforeSubmit: beforeSubmit,
        dataType: 'json',
        success: successHandler,
        error: errorHandler
      });
    });
  };
};



/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
__webpack_require__(11);
__webpack_require__(12);
__webpack_require__(13);
__webpack_require__(14);
module.exports = __webpack_require__(15);


/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__admin_clipboard__ = __webpack_require__(3);


window.App = {
	menu: __webpack_require__(4),
	auth: __webpack_require__(5),
	common: __webpack_require__(6),

	components: {
		admin_settings: __webpack_require__(7),
		admin_payment: __webpack_require__(8),
		admin_tests: __webpack_require__(9),
		admin_authors_upload: __webpack_require__(10)
	}

};

App.auth.init();
App.common.init();
Object(__WEBPACK_IMPORTED_MODULE_0__admin_clipboard__["a" /* default */])();

/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = init;
var fallbackCopyTextToClipboard = function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement('textarea');
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    document.body.removeChild(textArea);
    return successful;
  } catch (err) {
    document.body.removeChild(textArea);
    return false;
  }
};
var copyTextToClipboard = function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    return fallbackCopyTextToClipboard(text);
  }
  navigator.clipboard.writeText(text).then(function () {
    console.log('Async: Copying to clipboard was successful!');
    return true;
  }, function (err) {
    console.error('Async: Could not copy text: ', err);
    return false;
  });
};

function init(selectorButton, text) {
  $('[data-clipboard-action="copy"]').each(function () {
    var $button = $(this);
    $button.click(function (e) {
      copyTextToClipboard($($button.attr('data-clipboard-target')).val());
    });
  });
  /*
  $(selectorButton).click(e => {
      copyTextToClipboard(text);
      return false;
  })
  */
}

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateSite", function() { return updateSite; });
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var menuItems = [];

var openNode = function openNode($node) {
  $node.find('>UL').show();
};

/*
const getSiteSubtree = (item) => {
  item.items = [];

  item.services.map(service => {
    switch (service) {
      case 'callback':
        return item.items.push({
          name: 'Обратный звонок на сайте',
          type: 'callback',
          icon: 'icon-call-out',
          items: [
            {name: 'Статистика', url: `/admin/sites/${ item.id }/callback/stats`, icon: 'icon-bar-chart'},
            {name: 'Настройка сервиса', url: `/admin/sites/${ item.id }/callback/settings`, icon: 'icon-wrench'},
          ],
        });

      case 'form':
        return item.items.push({
          name: 'Формы и заявки на сайте',
          icon: 'icon-puzzle',
          items: [
            {name: 'Список форм', url: `/admin/sites/${ item.id }/forms/list`, icon: 'icon-notebook'},
            {name: 'Список заявок', url: `/admin/sites/${ item.id }/forms/requests`, icon: 'icon-notebook'},
            {name: 'Добавить форму', url: `/admin/sites/${ item.id }/forms/builder`, icon: 'fa fa-plus'},
            {name: 'Настройки сервиса', url: `/admin/sites/${ item.id }/forms/settings`, icon: 'icon-wrench'},
          ],
        });

      case 'social':
        return item.items.push({name: 'Социальные сети на сайте', url: `/admin/sites/${ item.id }/social/settings`, icon: 'icon-social-twitter'});

      case 'toolbar':
        return item.items.push({name: 'Сервис Тулбар', url: `/admin/sites/${ item.id }/toolbar/settings`, icon: 'fa fa-wrench'});
    }
  });

  item.items.push({name: 'Настройки сайта', url: `/admin/sites/${ item.id }/settings`, icon: 'icon-settings'});

  return item;
}
*/

var getTypeSubTree = function getTypeSubTree(item) {
  if (item.type === 'site') {
    item = getSiteSubtree(item);
  }

  return item;
};

var renderMenu = function renderMenu() {
  var $sidebar = $('#sidebar');
  var $balanceItem = $sidebar.find('.nav-item.start').remove();
  $sidebar.empty();
  $sidebar.append($balanceItem);

  var renderItem = function renderItem(item) {
    var $item = $('<li class="nav-item"></li>');
    var $link = $('<a href="#"> <span class="title"></span></a>');
    if (item.url) {
      $link.prop('href', item.url);
    } else if (item.modal) {
      $link.prop('href', '#' + item.modal).attr('data-toggle', 'modal');
    }

    $link.find('span').text(item.name);
    if (item.icon) {
      $link.prepend($('<i></i>').addClass(item.icon));
    }

    $item.append($link);
    item = getTypeSubTree(item);

    if (typeof item.items !== 'undefined') {
      var $list = $('<ul class="sub-menu" style="margin: 0"></ul>');
      $link.append('<span class="setClass arrow"></span>');

      item.items.map(function (subItem) {
        $list.append(renderItem(subItem));
      });

      $item.append($list);
    }

    return $item;
  };

  var items = menuItems.concat();

  var $items = items.map(function (item) {
    var $item = renderItem(item);
    $sidebar.append($item);
  });

  //const $activeLink = $sidebar.find('[href="' + document.location.pathname.replace(/\/$/, '') + '"]');
  var $activeLink = $sidebar.find('[href="' + document.location.pathname.replace(/^(\/[^\/]+\/[^\/]+\/[^\/]+).*$/, "$1") + '"]');
  if ($activeLink.length) {
    var $activeItem = $activeLink.parent();
    $activeItem.addClass('open');

    while ($activeItem.length) {
      openNode($activeItem);
      $activeItem = $activeItem.parent().closest('.nav-item');
    }
  }
};

var init = function init(menu) {

  menuItems = menu;
  renderMenu();

  var $menu = $('#sidebar');

  $menu.on({
    click: function click(e) {
      var $link = $(e.currentTarget);
      var url = $link.attr('href');
      if (url !== '#') return;

      $link.find('.arrow').toggleClass('open');
      $link.next('ul').toggle();
    }

  }, 'A');
};

var updateSite = function updateSite(siteId, services) {
  console.log('updating site with');
  console.log(services);

  menuItems = menuItems.map(function (item) {
    if (item.code === 'sites') {
      return _extends({}, item, {
        items: item.items.map(function (subItem) {
          if (subItem.id == siteId) {
            subItem.services = services;
            return subItem;
          } else {
            return subItem;
          }
        })
      });
    } else {
      return item;
    }
  });

  renderMenu();
};



/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
__webpack_require__(0).init();

var init = function init() {
	var $page = $('.page-auth');
	if (!$page.length) return;

	var $formLogin = $('#formLogin');
	var $formRegister = $('#formRegister');
	var $formRemind = $('#formRemindPassword');

	$('.button-register').click(function (e) {
		$('.alert').slideUp(300);
		$('#reminded').html('');
		$formRemind.hide();
		$formLogin.hide();
		$formRegister.show();
		$formRegister.find('input[name="email"]').focus();
		setLocation("/reg");
		return false;
	});

	$page.find('.button-login').click(function (e) {
		$('.alert').slideUp(300);
		$('#reminded').html('');
		$formRemind.hide();
		$formRegister.hide();
		$formLogin.show();
		$formLogin.find('input[name="email"]').focus();
		setLocation("/login");
		return false;
	});

	$page.find('.button-forget').click(function (e) {
		$('.alert').slideUp(300);
		$('#reminded').html('');
		$formLogin.hide();
		$formRegister.hide();
		$formRemind.show();
		setLocation("/reminder");
		return false;
	});

	$formLogin.find('input[name="email"]').focus();

	$formLogin.commonForm(function (data) {
		//console.log(data);
		if (data.url != "") {
			document.location.href = data.url;
		} else {
			document.location.href = '/admin';
		}
	});

	$formRegister.commonForm(function (data) {
		console.log(data);
		if (data.url != "") {
			document.location.href = data.url;
		} else {
			//document.location.href = '/admin/settings/personal/new';
			document.location.href = '/admin';
		}
	});

	$formRemind.commonForm(function (data) {
		//console.log(data);
		if (data.result == 'notfound') {
			$('#reminderError').html('Такой E-mail не зарегистрирован').removeClass('hidden');
		} else {
			if (data.url != "") {
				document.location.href = data.url;
			} else {
				document.location.href = '/admin/login?reminded';
			}
		}
	});

	/*
 $('#buttonLogout').click(e => {
 });
 */
};



function setLocation(curLoc) {
	try {
		history.pushState(null, null, curLoc);
		return;
	} catch (e) {}
	location.hash = '#' + curLoc;
}

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
var init = function init() {

  /*
    $('#formAddSitePopup').each(function() {
      const $form = $(this);
      const $popup = $form.closest('.modal');
  
      $popup.on('shown.bs.modal', e => {
        $form.find('input[type="text"]:eq(0)').focus();
      });
  
      $form.commonForm(data => {
        document.location.href = '/admin/sites/' + data.id + '/settings';
      })
  
    });
  */

  $('[data-toggle="tooltip"]').tooltip();
};



/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
var init = function init() {

	$('#formUpdateDefault').commonForm('Заполненные данные были успешно сохранены');
	$('#formUpdateDefault1').commonForm('Заполненные данные были успешно сохранены');
	$('#formUpdateDefault2').commonForm('Заполненные данные были успешно сохранены');
	$('#formUpdateDefault3').commonForm('Заполненные данные были успешно сохранены');

	$('#formUpdateProfile').commonForm('Личные данные были успешно сохранены');
	$('#formUpdateAvatar').commonForm('Аватар был успешно сохранён. Перезагрузите страницу чтобы увидеть новый аватар');
	$('#formUpdateAddress').commonForm('Почтовый адрес был успешно сохранён');

	$('#formUpdatePassword').commonForm(function (data, $form) {
		var $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
		$success.find('span').text('Пароль был успешно изменен');
		$('.alert-area').append($success);
		setTimeout(function () {
			return $success.fadeOut(500, function () {
				return $success.remove();
			});
		}, 10000);
		$form.find('input[type="password"]').val('');
	});

	$('#formUpdateAfillate').commonForm(function (data, $form) {
		if (data.result == 'success') {
			var $alert = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
			$alert.find('span').text('Телефон партнёра был успешно изменен');
			$('.alert-area').append($alert);
			setTimeout(function () {
				return $alert.fadeOut(500, function () {
					return $alert.remove();
				});
			}, 10000);
			$('#divUpdateAfillate').slideUp(500);
		} else if (data.result == 'notfound') {
			var _$alert = $('<div class="custom-alerts alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
			_$alert.find('span').text('Партнёр с таким телефоном отсутствует в базе данных. Пожалуйста проверь, правильно ли был введён номер телефона?');
			$('.alert-area').append(_$alert);
			setTimeout(function () {
				return _$alert.fadeOut(500, function () {
					return _$alert.remove();
				});
			}, 10000);
		}
	});
};


/***/ }),
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
var init = function init() {

  $('#formPayment').commonForm(function (data) {
    document.location.href = data.url;
  });
};



/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "start", function() { return start; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "test", function() { return test; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "results", function() { return results; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__admin_req__ = __webpack_require__(0);


var start = function start(options) {
  $('#buttonStart').click(function () {
    console.log('caoasooo');
    Object(__WEBPACK_IMPORTED_MODULE_0__admin_req__["sendPost"])('/admin/exam/' + options.test_id + '/start', {}).then(function (data) {
      document.location.href = '/admin/exam/' + options.test_id + '/' + data.session;
    }).catch(function (e) {
      alert(e.responseJSON.reason);
    });

    return false;
  });
};

var test = function test(options) {
  console.log('test-initted');

  var $counter = $('.question-counter');
  var currentTimer = $counter.text() * 1;
  var endTime = currentTimer * 1000 + new Date().getTime();

  var submitAnswer = function submitAnswer() {
    var answers = $form.find('input:checked').map(function (i, item) {
      return item.value;
    }).toArray();
    // if (!answers.length) return false;

    Object(__WEBPACK_IMPORTED_MODULE_0__admin_req__["sendPost"])($form.prop('action'), { answer: answers, crash: true }).then(function (data) {
      document.location.reload();
    }).catch(function (e) {
      console.log(e.responseJSON);
    });

    return false;
  };

  var timer = setInterval(function () {
    var currentTime = new Date().getTime();
    var timeLeft = Math.max(0, Math.round((endTime - currentTime) / 1000));
    if (timeLeft) {
      $counter.text(timeLeft);
    } else {
      clearInterval(timer);
      submitAnswer();
    }
  }, 600);

  var $form = $('#formAnswer');
  $form.submit(submitAnswer);

  $('#buttonStart').click(function () {
    Object(__WEBPACK_IMPORTED_MODULE_0__admin_req__["sendPost"])('/admin/exam/' + options.test_id + '/start', {}).then(function (data) {
      document.location.href = '/admin/exam/' + options.test_id + '/' + data.session;
    }).catch(function (e) {
      alert(e.responseJSON.reason);
    });

    return false;
  });
};

var results = function results(options) {
  var $table = $('#tableTestResults');

  $table.on({
    click: function click(e) {
      var $el = $(e.target);
      if ($el.is('.btn-group') || $el.closest('.btn-group').length) return;

      document.location.href = '/admin/results/' + $(e.currentTarget).attr('data-item-id');
    }

  }, 'tr[data-item-id]');
};



/***/ }),
/* 10 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
var init = function init() {

  $('#formExamUpload').commonForm(function (data, $form) {
    var $success = $('<div class="custom-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><i class="fa-lg fa fa-fa fa-check"></i> <span></span></div>');
    $success.find('span').text('Файл был успешно загружен, новый тест-экзамен успешно был создан. Не забудьте обязательно проверить его работоспособность');
    $('.alert-area').append($success);
    setTimeout(function () {
      return $success.fadeOut(500, function () {
        return $success.remove();
      });
    }, 10000);
    $form.find('input[type="file"]').val('');
    $form.find('input[name="name"]').val('');
    $form.find('input[name="name"]').val('');
  });
};



/***/ }),
/* 11 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 12 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 13 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 14 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 15 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);