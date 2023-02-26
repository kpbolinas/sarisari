import { reactive } from "vue";

export default reactive({
    itemIDs: [],
    data: {
        items: {},
        totalPrice: 0,
    },
    add(item) {
        if (this.itemIDs.includes(item?.id)) {
            let data = this.data.items[item?.id];
            // add quantity to the product already in the cart list
            data.quantity++;
            // compute total price per item
            data.total = (
                parseFloat(data?.total) + parseFloat(item?.price)
            ).toFixed(2);
        } else {
            const newItem = { quantity: 1, item: item, total: item?.price };
            // add product to cart
            this.data.items[item?.id] = newItem;
            // register product to cart list for checking
            this.itemIDs.push(item?.id);
        }
        // compute total price
        this.data.totalPrice = (
            parseFloat(this.data.totalPrice) + parseFloat(item?.price)
        ).toFixed(2);
        // update session storage
        this.updateSession();
    },
    deduct(item) {
        if (this.itemIDs.includes(item?.id)) {
            let data = this.data.items[item?.id];
            // deduct quantity to the product already in the cart list
            data.quantity--;
            if (data.quantity === 0) {
                // unregister product to cart list for checking
                this.itemIDs = this.itemIDs.filter((e) => e !== item?.id);
                // remove item in the cart list
                delete this.data.items[item?.id];
            } else {
                // recompute total price per item
                data.total = (
                    parseFloat(data?.total) - parseFloat(item?.price)
                ).toFixed(2);
            }
            // recompute total price
            this.data.totalPrice = (
                parseFloat(this.data.totalPrice) - parseFloat(item?.price)
            ).toFixed(2);
            // update session storage
            this.updateSession();
        }
    },
    remove(item) {
        if (this.itemIDs.includes(item?.id)) {
            let data = this.data.items[item?.id];
            // unregister product to cart list for checking
            this.itemIDs = this.itemIDs.filter((e) => e !== item?.id);
            // recompute total price
            this.data.totalPrice = (
                parseFloat(this.data.totalPrice) - parseFloat(data?.total)
            ).toFixed(2);
            // remove item in the cart list
            delete this.data.items[item?.id];
            // update session storage
            this.updateSession();
        }
    },
    initSession() {
        const cartData = sessionStorage.getItem("cart-data");
        let cartSession = cartData ? JSON.parse(cartData) : {};
        const session = {
            itemIDs: cartSession.itemIDs ?? [],
            items: cartSession.items ?? {},
            totalPrice: cartSession.totalPrice ?? 0,
        };
        sessionStorage.setItem("cart-data", JSON.stringify(session));
        this.itemIDs = session.itemIDs;
        this.data = {
            items: session.items,
            totalPrice: session.totalPrice,
        };
    },
    updateSession() {
        const session = {
            itemIDs: this.itemIDs,
            ...this.data,
        };
        sessionStorage.setItem("cart-data", JSON.stringify(session));
    },
    clear() {
        // reset product registry
        this.itemIDs = [];
        // reset data
        this.data = {
            items: {},
            totalPrice: 0,
        };
        // update session storage
        this.updateSession();
    },
});
