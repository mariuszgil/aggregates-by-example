package io.pillopl.firstdesign;

import java.util.ArrayList;
import java.util.List;
import java.util.Objects;
import java.util.stream.Collectors;
import java.util.stream.Stream;

class Cart {

    private final CartId cartId;
    private List<Item> items = new ArrayList<>();
    private List<Item> freeItems = new ArrayList<>();

    private List<Item> returnedFreeItems = new ArrayList<>();

    Cart(CartId cartId) {
        this.cartId = cartId;
    }

    void add(Item item) {
        if (returnedFreeItems.contains(item)) {
            returnedFreeItems.remove(item);
            freeItems.add(item);
        } else {
            items.add(item);
        }
    }

    void addFree(Item item) {
        freeItems.add(item);
    }

    void remove(Item item) {
        if (!items.contains(item)) {
            if (freeItems.contains(item)) {
                freeItems.remove(item);
                returnedFreeItems.add(item);
            }
        } else {
            items.remove(item);
        }
    }

    void removeFree(Item item) {
        freeItems.remove(item);
    }

    String print() {
        return Stream.concat(
                items.stream().map(item -> item.name).sorted(),
                freeItems.stream().map(item -> "[FREE] " + item.name).sorted())
                .collect(Collectors.joining(", "));

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

