package io.pillopl.seconddesign;

import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import static java.util.UUID.randomUUID;
import static org.junit.jupiter.api.Assertions.assertEquals;

class CartTest {

    CartId cartId = new CartId(randomUUID());

    CartDatabase cartRepository = new CartDatabase();
    ExtraItemsPolicy extraItemsPolicy = new ExtraItemsPolicy();
    CartCommandsHandler readModel = new CartCommandsHandler(cartRepository, extraItemsPolicy);

    final Item DELL_XPS = new Item("DELL XPS");
    final Item BAG = new Item("BAG");

    @Test
    @DisplayName("Can add an item")
    void canAdd() throws Exception {
        //given
        Cart cart = aCart();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{DELL XPS=1}", cart.print());
    }

    @Test
    @DisplayName("Can add two same items")
    void canAddTwoSameItems() throws Exception {
        //given
        Cart cart = aCart();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{DELL XPS=2}", cart.print());
    }

    @Test
    @DisplayName("Can remove an item")
    void canRemove() throws Exception {
        //given
        Cart cart = aCart();
        //and
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //when
        readModel.handle(new RemoveItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{}", cart.print());
    }

    @Test
    @DisplayName("Can remove two same items")
    void canRemoveTwoSameItems() throws Exception {
        //given
        Cart cart = aCart();
        //and
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //when
        readModel.handle(new RemoveItemCommand(cartId, DELL_XPS));
        readModel.handle(new RemoveItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{}", cart.print());
    }


    @Test
    @DisplayName("When adding a laptop, bag is added for free")
    void laptopBagIsFreeItem() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{DELL XPS=1, [FREE] BAG=1}", cart.print());
    }

    @Test
    @DisplayName("Two laptops means 2 bags")
    void twoLaptopsMeansTwoBags() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));

        //then
        assertEquals("{DELL XPS=2, [FREE] BAG=2}", cart.print());
    }

    @Test
    @DisplayName("Can remove free bag")
    void canRemoveFreeBag() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new IntentionallyRemoveFreeItemCommand(cartId, BAG));

        //then
        assertEquals("{DELL XPS=1}", cart.print());
    }

    @Test
    @DisplayName("Can remove just one free bag")
    void canRemoveJustOneOfTwoFreeBags() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new IntentionallyRemoveFreeItemCommand(cartId, BAG));

        //then
        assertEquals("{DELL XPS=2, [FREE] BAG=1}", cart.print());
    }

    @Test
    @DisplayName("I want my free bag back")
    void wantsMyFreeBagBack() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new IntentionallyRemoveFreeItemCommand(cartId, BAG));
        readModel.handle(new AddFreeItemBackCommand(cartId, BAG));

        //then
        assertEquals("{DELL XPS=2, [FREE] BAG=2}", cart.print());
    }

    @Test
    @DisplayName("Already has 2 free bags (and 2 laptops), wants just one new bag!")
    void twoBagsAreFreeAndWantsAnotherOne() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, BAG));

        //then
        assertEquals("{DELL XPS=2, [FREE] BAG=2, BAG=1}", cart.print());
    }

    @Test
    @DisplayName("Has 2 free bags, removes one, adds it back, adds additional bag, removes it and adds back and removes")
    void eveyrthingComplex() throws Exception {
        //given
        Cart cart = aCart();
        //and
        freeBagDiscountIsEnabled();

        //when
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new AddItemCommand(cartId, DELL_XPS));
        readModel.handle(new IntentionallyRemoveFreeItemCommand(cartId, BAG));
        readModel.handle(new AddFreeItemBackCommand(cartId, BAG));
        readModel.handle(new AddItemCommand(cartId, BAG));
        readModel.handle(new RemoveItemCommand(cartId, BAG));
        readModel.handle(new AddItemCommand(cartId, BAG));
        readModel.handle(new RemoveItemCommand(cartId, BAG));

        //then
        assertEquals("{DELL XPS=2, [FREE] BAG=2}", cart.print());
    }

    @Test
    @DisplayName("Try to hack the system and sent AddFreeItemBackCommand")
    void cannotHackTheSystemAndJustSendWrongEvent() throws Exception {
        //given
        Cart cart = aCart();

        //when
        readModel.handle(new AddFreeItemBackCommand(cartId, BAG));

        //then
        assertEquals("{}", cart.print());
    }

    void freeBagDiscountIsEnabled() {
        extraItemsPolicy.add(new ExtraItem(DELL_XPS, BAG));
    }

    Cart aCart() {
        Cart view = new Cart(cartId);
        cartRepository.save(cartId, view);
        return view;
    }

}