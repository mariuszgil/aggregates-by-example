package io.pillopl.seconddesign;


import java.util.HashSet;
import java.util.Objects;
import java.util.Set;

import static java.util.stream.Collectors.toSet;

class ExtraItemsPolicy {

    private Set<ExtraItem> extraItems = new HashSet<>();

    Set<Item> findAllFor(Item item) {
        return extraItems
                .stream()
                .filter(extraItem -> extraItem.isFreeFor(item))
                .map(extraItem -> extraItem.freeItem)
                .collect(toSet());
    }

    void add(ExtraItem extraItem) {
        extraItems.add(extraItem);
    }
}


class ExtraItem {

    final Item forItem;
    final Item freeItem;

    ExtraItem(Item item, Item freeItem) {
        this.forItem = item;
        this.freeItem = freeItem;
    }

    boolean isFreeFor(Item item) {
        return forItem.equals(item);
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ExtraItem extraItem = (ExtraItem) o;
        return Objects.equals(forItem, extraItem.forItem) &&
                Objects.equals(freeItem, extraItem.freeItem);
    }
}
