

	//--  --  --  --  --  --  --  --  --  --  --  --
	// Функция обновляет панель пользователя
	gui.edit.changeUserPanel = function(userName, userCash) {
		toEdit("#account-name").setText(userName);
		toEdit("#account-cash").setText(userCash);
	}


	// Функция добавляет кнопку в меню
	gui.edit.addMenuButton = function(title, icon, link) {

		var el = newElem("button .menu-btn", "#panel-menu", `
			<img src="/icons/${icon}.svg">
			<span> ${title} </span>
		`);

		el.onclick = function() {
			gui.toLink(link);
		};


		var mobileEl = newElem("img .mobile-btn", "#mobile-menu");
		mobileEl.src = `/icons/${icon}.svg`;

		mobileEl.onclick = function() {
			gui.toLink(link);
		};
	}


	// Функция обновления приложения
	gui.edit.update = async function() {
		// Получить информацию о пользователе
		gui.info.userInfo = await api.usersGetInfo();

		// Если авторизации нет
		if (typeof(gui.info.userInfo) == "string") {
			gui.edit.changeUserPanel("Гость", "Авторизоваться?");

		} else {
			const userNick = gui.info.userInfo.nickname;
			const userCash = gui.info.userInfo.cash;

			gui.edit.changeUserPanel(userNick, `${userCash} коинов`);
		}
	}