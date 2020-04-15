package io.pillopl.firstdesign;

import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import static java.util.UUID.randomUUID;
import static org.junit.jupiter.api.Assertions.assertEquals;

class CartTest {

    CartId cartId = new CartId(randomUUID());
    CartDatabase cartRepository = new CartDatabase();
    ExtraItemsPolicy extraItemsPolicy = new ExtraItemsPolicy();
    CartCommandsHandler commandHandler = new CartCommandsHandler(cartRepository, extraItemsPolicy);

    final Item DELL_XPS = new Item("DELL XPS");
    final Item BAG = new Item("BAG");

    @Test
    @DisplayName("Can add an item")
    void canAdd() throws Exception {
        //given
        Cart cart = aCartView();

        //when
        commandHandler.addItem(cartId, DELL_XPS);

        //then
        assertEquals("DELL XPS", cart.print());
    }

    @Test
    @DisplayName("Can add two same items")
    void canAddTwoSameItems() throws Exception {
        //given
        Cart cart = aCartView();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);

        //then
        assertEquals("DELL XPS, DELL XPS", cart.print());
    }

    @Test
    @DisplayName("Can remove an item")
    void canRemove() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        commandHandler.addItem(cartId, DELL_XPS);

        //when
        commandHandler.removeItem(cartId, DELL_XPS);

        //then
        assertEquals("", cart.print());
    }

    @Test
    @DisplayName("Can remove two same items")
    void canRemoveTwoSameItems() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);

        //when
        commandHandler.removeItem(cartId, DELL_XPS);
        commandHandler.removeItem(cartId, DELL_XPS);

        //then
        assertEquals("", cart.print());
    }

    @Test
    @DisplayName("When adding a laptop, bag is added for free")
    void laptopBagIsFreeItem() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);

        //then
        assertEquals("DELL XPS, [FREE] BAG", cart.print());
    }

    @Test
    @DisplayName("Two laptops means 2 bags")
    void twoLaptopsMeansTwoBags() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);

        //then
        assertEquals("DELL XPS, DELL XPS, [FREE] BAG, [FREE] BAG", cart.print());
    }

    @Test
    @DisplayName("Can remove free bag")
    void canRemoveFreeBag() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.removeItem(cartId, BAG);

        //then
        assertEquals("DELL XPS", cart.print());
    }

    @Test
    @DisplayName("Can remove just one free bag")
    void canRemoveJustOneOfTwoFreeBags() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.removeItem(cartId, BAG);

        //then
        assertEquals("DELL XPS, DELL XPS, [FREE] BAG", cart.print());
    }

    @Test
    @DisplayName("I want my free bag back")
    void wantsMyFreeBagBack() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.removeItem(cartId, BAG);
        commandHandler.addItem(cartId, BAG);

        //then
        assertEquals("DELL XPS, DELL XPS, [FREE] BAG, [FREE] BAG", cart.print());
    }

    @Test
    @DisplayName("Already has 2 free bags (and 2 laptops), wants just one new bag!")
    void twoBagsAreFreeAndWantsAnotherOne() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, BAG);

        //then
        assertEquals(
                "BAG, " +
                        "DELL XPS, " +
                        "DELL XPS, " +
                        "[FREE] BAG, " +
                        "[FREE] BAG", cart.print());
    }

    @Test
    @DisplayName("Has 2 free bags, removes one, adds it back, adds additional bag, removes it and adds back")
    void eveyrthingComplex() throws Exception {
        //given
        Cart cart = aCartView();
        //and
        freeBagDiscountIsEnabled();

        //when
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.addItem(cartId, DELL_XPS);
        commandHandler.removeItem(cartId, BAG);
        commandHandler.addItem(cartId, BAG);
        commandHandler.addItem(cartId, BAG);
        commandHandler.removeItem(cartId, BAG);
        commandHandler.addItem(cartId, BAG);

        //then
        assertEquals(
                "BAG, " +
                        "DELL XPS, " +
                        "DELL XPS, " +
                        "[FREE] BAG, " +
                        "[FREE] BAG", cart.print());
    }

    void freeBagDiscountIsEnabled() {
        extraItemsPolicy.add(new ExtraItem(DELL_XPS, BAG));
    }

    Cart aCartView() {
        Cart view = new Cart(cartId);
        cartRepository.save(cartId, view);
        return view;
    }

}