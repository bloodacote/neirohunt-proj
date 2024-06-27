

// Апихук
class ApiHook {
	constructor() {
		this.apiDir = "/api";
		this.token = "";
		this.getCookieToken();
	}

	async load(url, data) {
		data.token = this.token;
		var result = await loadAPI(this.apiDir + url, data);

		if (result.status >= 400) {
			return result.errors[0];
		} else {
			return result.data;
		}
	}





	// = РАБОТА С ТОКЕНАМИ =
	//--  --  --  --  --  --  --  --  --  --  --  --

	async getToken() {
		return this.token;
	}

	async setToken(newToken) {
		this.token = newToken;
		return newToken;
	}

	async getCookieToken() {
		const userToken = getCookie("token");

		if (userToken != null) {
			this.token = userToken;
		} else {
			this.token = "";
		}
	}

	async setCookieToken(token) {
		setCookie("token", token);
		this.getCookieToken();
	}





	// = MODELS =
	//--  --  --  --  --  --  --  --  --  --  --  --

	async modelsFinder(text){
		return await this.load("/models/finder.php", {
			text: text
		});
	}

	async modelsCounter(text){
		return await this.load("/models/counter.php", {
			text: text
		});
	}





	// = USERS =
	//--  --  --  --  --  --  --  --  --  --  --  --

	async usersCreate(nickname, pass, pass2, email) {
		return await this.load("/users/create.php", {
			nickname: nickname,
			pass: pass,
			pass2: pass2,
			email: email
		});
	}

	async usersVerifyEmail(email, code) {
		return await this.load("/users/verify_email.php", {
			email: email,
			code: code
		});
	}

	async usersLogin(nickname, pass) {
		var result = await this.load("/users/login.php", {
			nickname: nickname,
			pass: pass
		});

		this.setCookieToken(result.token); // Вход
		return result;
	}

	async usersLogout() {
		this.setCookieToken("");
		return true;
	}

	async usersChangePass(pass, pass2) {
		return await this.load("/users/change_pass.php", {
			pass: pass,
			pass2: pass2
		});
	}

	async usersChangeNick(nick) {
		return await this.load("/users/change_nick.php", {
			nick: nick
		});
	}

	async usersGetInfo() {
		return await this.load("/users/get_info.php", {});
	}



}

const api = new ApiHook();