export default class OrderApiService {
    // Checkout API
    static checkout = (requestData) =>
        window.axios.post("/api/orders", requestData);
}
