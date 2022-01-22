import Router from "../Router";

export default class Profile extends Router{
    async me() {
        // Set tokens
        return await this.connection.GET('/profile');
    }
}

