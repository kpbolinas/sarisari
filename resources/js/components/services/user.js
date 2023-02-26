export default class UserApiService {
    // Login API
    static login = (requestData) =>
        window.axios.post("/api/login", requestData);

    // Logout API
    static logout = (config = {}) =>
        window.axios.post("/api/logout", {}, config);

    // Forgot Password API
    static forgotPassword = (requestData) =>
        window.axios.post("/api/forgot-password", requestData);

    // Profile API
    static profile = () => window.axios.get("/api/profile");

    // Edit Info API
    static editInfo = (requestData) =>
        window.axios.patch("/api/edit-info", requestData);

    // Change Password API
    static changePassword = (requestData) =>
        window.axios.patch("/api/change-password", requestData);

    // Register API
    static register = (requestData) =>
        window.axios.post("/api/register", requestData);

    // Delete API
    static delete = () => window.axios.delete("/api/delete-account");
}
