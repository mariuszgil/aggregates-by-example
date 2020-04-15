package io.pillopl.seconddesign;

import java.util.HashMap;
import java.util.Map;

class CartDatabase {

    private final Map<CartId, Cart> views = new HashMap<>();

    Cart findViewBy(CartId cartId) {
        return views.get(cartId);
    }

    void save(CartId cardId, Cart view) {
        views.put(cardId, view);
    }
}
