export default class ProductApiService {
    // List API
    static list = (params) => window.axios.get("/api/products" + params);

    // Create API
    static create = (requestData) =>
        window.axios.post("/api/products", requestData);

    // Update API
    static update = (id, requestData) =>
        window.axios.patch("/api/products/" + id, requestData);

    // Delete API
    static delete = (id) => window.axios.delete("/api/products/" + id);
}
