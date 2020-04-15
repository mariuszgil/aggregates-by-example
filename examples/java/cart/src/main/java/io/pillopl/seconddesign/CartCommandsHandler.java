package io.pillopl.seconddesign;

import java.util.Set;

//in real-life project is better to have command handler as a separate class
class CartCommandsHandler {

    private final CartDatabase cartDatabase;
    private final ExtraItemsPolicy extraItemsPolicy;

    CartCommandsHandler(CartDatabase cartRepository, ExtraItemsPolicy extraItems) {
        this.cartDatabase = cartRepository;
        this.extraItemsPolicy = extraItems;
    }

    void handle(AddItemCommand cmd) {
        Cart cartView = cartDatabase.findViewBy(cmd.cartId);
        cartView.add(cmd.addedItem);
        Set<Item> freeItems = extraItemsPolicy.findAllFor(cmd.addedItem);
        freeItems.forEach(cartView::addFree);
        cartDatabase.save(cmd.cartId, cartView);
    }

    void handle(RemoveItemCommand cmd) {
        Cart cartView = cartDatabase.findViewBy(cmd.cartId);
        cartView.remove(cmd.removedItem);
        Set<Item> freeItems = extraItemsPolicy.findAllFor(cmd.removedItem);
        freeItems.forEach(cartView::removeFreeItem);
        cartDatabase.save(cmd.cartId, cartView);
    }

    void handle(IntentionallyRemoveFreeItemCommand cmd) {
        Cart cartView = cartDatabase.findViewBy(cmd.cartId);
        cartView.removeFreeItemIntentionally(cmd.removedItem);
        cartDatabase.save(cmd.cartId, cartView);
    }

    void handle(AddFreeItemBackCommand cmd) {
        Cart cartView = cartDatabase.findViewBy(cmd.cartId);
        cartView.addFreeItemBack(cmd.addedItem);
        cartDatabase.save(cmd.cartId, cartView);
    }


}

class AddItemCommand {
    final CartId cartId;
    final Item addedItem;

    AddItemCommand(CartId cartId, Item addedItem) {
        this.cartId = cartId;
        this.addedItem = addedItem;
    }
}

class RemoveItemCommand {
    final CartId cartId;
    final Item removedItem;

    RemoveItemCommand(CartId cartId, Item removedItem) {
        this.cartId = cartId;
        this.removedItem = removedItem;
    }
}

class IntentionallyRemoveFreeItemCommand {
    final CartId cartId;
    final Item removedItem;

    IntentionallyRemoveFreeItemCommand(CartId cartId, Item removedItem) {
        this.cartId = cartId;
        this.removedItem = removedItem;
    }
}

class AddFreeItemBackCommand {
    final CartId cartId;
    final Item addedItem;

    AddFreeItemBackCommand(CartId cartId, Item addedItem) {
        this.cartId = cartId;
        this.addedItem = addedItem;
    }
}

//changes of quantity:
//ChangeQuantity from 3 -> 5 == AddFreeItemBackCommand, AddFreeItemBackCommand
//ChangeQuantity from 5 -> 4 == IntentionallyRemoveFreeItemCommand
