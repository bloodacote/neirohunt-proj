

class GuiHook {
	constructor() {
		//--  --  --  --  --  --  --  --  --  --  --  --
		// Инициализация
		this.edit = {};
		this.info = {};

		this.navigator = new LinkNavigator("/pages/home.html", "/pages/404.html");
		this.elemWindow = toEdit("#screen-window");
	}

	async init() {
		var pageMap = await loadJSON("/pagemap.json");
		this.navigator.setMap(pageMap);

		var siteData = await loadJSON("/main-data.json");
		this.info.siteData = siteData;
	}


	async loadPage(pagePath) {
		var pageCode = await loadURL(pagePath, null, "GET");

		this.elemWindow.setHTML(pageCode);
		runScriptsFromHTML(pageCode);

		return pageCode;
	}

	async toLink(link) {
		this.toggleLoading(true);
		var pagePath = this.navigator.findLink(link);

		changeLink(link);
		await this.loadPage(pagePath);

		this.toggleLoading(false);
	}

	async toggleLoading(isLoading) {
		if (isLoading == true) {
			toEdit("#screen-loading").removeClass("closed");
			toEdit("#screen-window").addClass("closed");

		} else {
			toEdit("#screen-loading").addClass("closed");
			toEdit("#screen-window").removeClass("closed");
		}
	}

	getError(errorTag, errorList) {
		var errorText = "Неизвестная ошибка";

		for (let [errorKey, errorValue] of Object.entries(errorList)) {
			if (errorTag == errorKey) {
				errorText = errorValue;
				console.log(`[ERROR] > ${errorValue}`);
			}
		}

		var el = newElem(".error-warn", "body", `
			<b> Ошибка! </b> ${errorText}
		`);

		el.onanimationend = function() {
			toEdit(el).delete();
		};
	}

	// Разделяет большое число по разрядам
	numSplit(num, splitter = ".") {
		var result = "";
		var isNegative = false;

		// Убираем минус, чтобы не было багов
		if (num < 0) {
			num -= num * 2 * (num < 0);
			isNegative = true;
		}

		// Расчёт
		num = String(num).split("").reverse();
		for (let i = 0; i < num.length; i++) {
			result = num[i] + result;

			if ((i + 1) % 3 == 0 && (i != num.length - 1)) {
				result = splitter + result;
			}
		}

		// Добавляем минус, если негативное число
		result = "-".repeat(isNegative) + result;
		return result;
	}

}

const gui = new GuiHook();