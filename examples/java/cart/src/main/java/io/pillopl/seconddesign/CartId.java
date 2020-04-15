package io.pillopl.seconddesign;

import java.util.Objects;
import java.util.UUID;


class CartId {

    private final UUID uuid;

    CartId(UUID uuid) {
        this.uuid = uuid;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        CartId cartId = (CartId) o;
        return Objects.equals(uuid, cartId.uuid);
    }
}

