package io.pillopl.seconddesign;

import java.util.ArrayList;
import java.util.List;
import java.util.Objects;
import java.util.stream.Stream;

import static java.util.function.Function.identity;
import static java.util.stream.Collectors.counting;
import static java.util.stream.Collectors.groupingBy;

class Cart {

    private final CartId cartId;
    private List<Item> items = new ArrayList<>();
    private List<Item> freeItems = new ArrayList<>();
    private List<Item> intentionallyRemovedFreeItems = new ArrayList<>();

    Cart(CartId cartId) {
        this.cartId = cartId;
    }

    void add(Item item) {
        items.add(item);
    }

    void addFree(Item item) {
        freeItems.add(item);
    }

    void remove(Item item) {
        items.remove(item);
    }

    void removeFreeItem(Item item) {
        freeItems.remove(item);
    }

    void removeFreeItemIntentionally(Item removedItem) {
        if (freeItems.contains(removedItem)) {
            freeItems.remove(removedItem);
            intentionallyRemovedFreeItems.add(removedItem);
        }
    }

    void addFreeItemBack(Item freeRemovedItem) {
        if (itemWasPreviouslyRemoved(freeRemovedItem)) {
            intentionallyRemovedFreeItems.remove(freeRemovedItem);
            freeItems.add(freeRemovedItem);
        }
    }

    private boolean itemWasPreviouslyRemoved(Item freeRemovedItem) {
        return intentionallyRemovedFreeItems.contains(freeRemovedItem);
    }

    String print() {
        return Stream
                .concat(
                        items.stream().map(item -> item.name).sorted(),
                        freeItems.stream().map(item -> "[FREE] " + item.name).sorted()
                ).collect(groupingBy(identity(), counting()))
                .toString();

    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Cart cart = (Cart) o;
        return Objects.equals(cartId, cart.cartId);
    }
}

class Item {

    final String name;

    Item(String name) {
        this.name = name;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Item item = (Item) o;
        return Objects.equals(name, item.name);
    }
}

