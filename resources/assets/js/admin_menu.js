

let menuItems = [];


const openNode = ($node) => {
  $node.find('>UL').show();
}


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




const getTypeSubTree = (item) => {
  if (item.type === 'site') {
    item = getSiteSubtree(item);
  }

  return item;
}




const renderMenu = () => {
  const $sidebar = $('#sidebar');
  const $balanceItem = $sidebar.find('.nav-item.start').remove();
  $sidebar.empty();
  $sidebar.append($balanceItem);

  const renderItem = (item) => {
    const $item = $('<li class="nav-item"></li>');
    const $link = $('<a href="#"> <span class="title"></span></a>');
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
      const $list = $('<ul class="sub-menu" style="margin: 0"></ul>');
      $link.append('<span class="setClass arrow"></span>');

      item.items.map(subItem => {
        $list.append(renderItem(subItem));
      });

      $item.append($list);
    }

    return $item;
  }

  const items = menuItems.concat();

  const $items = items.map(item => {
    const $item = renderItem(item);
    $sidebar.append($item);
  });

//const $activeLink = $sidebar.find('[href="' + document.location.pathname.replace(/\/$/, '') + '"]');
const $activeLink = $sidebar.find('[href="' + document.location.pathname.replace(/^(\/[^\/]+\/[^\/]+\/[^\/]+).*$/, "$1") + '"]');
  if ($activeLink.length) {
    let $activeItem = $activeLink.parent();
    $activeItem.addClass('open');

    while ($activeItem.length) {
      openNode($activeItem);
      $activeItem = $activeItem.parent(). closest('.nav-item');
    }
  }
}






const init = (menu) => {

  menuItems = menu;
  renderMenu();

  const $menu = $('#sidebar');

  $menu.on({
    click: e => {
      const $link = $(e.currentTarget);
      const url = $link.attr('href');
      if (url !== '#') return;

      $link.find('.arrow').toggleClass('open');
      $link.next('ul').toggle();
    }

  }, 'A');
}






const updateSite = (siteId, services) => {
  console.log('updating site with');
  console.log(services);

  menuItems = menuItems.map(item => {
    if (item.code === 'sites') {
      return {
        ...item,
        items: item.items.map(subItem => {
          if (subItem.id == siteId) {
            subItem.services = services;
            return subItem;

          } else {
            return subItem;
          }
        }),
      }

    } else {
      return item;
    }
  });

  renderMenu();
}







export {
  init,
  updateSite,
}