package io.pillopl.firstdesign;

import java.util.Set;

//in real-life project is better to have command handler as a separate class
class CartCommandsHandler {

    private final CartDatabase cartDatabase;
    private final ExtraItemsPolicy extraItemsPolicy;

    CartCommandsHandler(CartDatabase cartRepository, ExtraItemsPolicy extraItems) {
        this.cartDatabase = cartRepository;
        this.extraItemsPolicy = extraItems;
    }

    void addItem(CartId cartId, Item addedItem) {
        Cart cartView = cartDatabase.findViewBy(cartId);
        cartView.add(addedItem);
        Set<Item> freeItems = extraItemsPolicy.findAllFor(addedItem);
        freeItems.forEach(cartView::addFree);
        cartDatabase.save(cartId, cartView);
    }

    void removeItem(CartId cartId, Item removedItem) {
        Cart cartView = cartDatabase.findViewBy(cartId);
        cartView.remove(removedItem);
        Set<Item> freeItems = extraItemsPolicy.findAllFor(removedItem);
        freeItems.forEach(cartView::removeFree);
        cartDatabase.save(cartId, cartView);
    }

}
