import api from "../api";
export default {
    init: async () => {
        await api.connection();
        return false;
    },
    login: async (tokens) => {
         await api.connection.tokenManager.updateAccess(tokens);
         const response = await api.profile.show();
         return response.data;

    },
    logout: () => {
        return api.connection.tokenManager.removeAccess();

    },
    checkError: ({ status }) => {
        return status === 401 || status === 403
            ? Promise.reject()
            : Promise.resolve();
    },
    checkAuth: () => {
        return api.connection.tokenManager.hasToken();
    },
    getRole: () => {
        return this.profile.role;
    },
};
