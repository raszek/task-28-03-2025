export class Favourites {

    favouriteMealIds = [];

    handlePageLoad() {
        this.favouriteMealIds = this.loadFavouriteIds();

        const favourites = document.querySelectorAll('.favourite');

        for (const favourite of favourites) {
            this.setActive(favourite);
            favourite.onclick = this.bindToggle(this);
        }

        this.loadFavouriteButton();
    }

    getFavouriteButton() {
        const favouriteButton = document.getElementById('favourite-button');

        if (!favouriteButton) {
            throw new Error('Favourite button not found');
        }

        return favouriteButton;
    }

    loadFavouriteButton() {
        const favouriteButton = this.getFavouriteButton();

        favouriteButton.onclick = this.loadFavourites.bind(this);
    }

    loadFavourites() {
        const favouriteButton = this.getFavouriteButton();

        const path = favouriteButton.getAttribute('data-path');

        const urlParams = new URLSearchParams({
            id: this.favouriteMealIds
        });

        window.location.href = `${path}?${urlParams.toString()}`;
    }

    bindToggle(context) {
        function toggle() {
            const favouriteId = this.getAttribute('data-meal-id');

            if (!favouriteId) {
                throw new Error('Favourite id not found');
            }

            if (context.isFavourite(this)) {
                this.classList.add('btn-outline-primary');
                this.classList.remove('btn-primary');
                context.removeFavourite(favouriteId);
            } else {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
                context.addFavourite(favouriteId);
            }
        }

        return toggle;
    }

    isFavourite(item) {
        const favouriteId = item.getAttribute('data-meal-id');

        return this.favouriteMealIds.includes(favouriteId);
    }

    setActive(item) {
        if (this.isFavourite(item)) {
            item.classList.remove('btn-outline-primary');
            item.classList.add('btn-primary');
        } else {
            item.classList.add('btn-outline-primary');
            item.classList.remove('btn-primary');
        }
    }

    addFavourite(favouriteId) {
        this.favouriteMealIds.push(favouriteId);

        this.saveFavouriteIds(this.favouriteMealIds);
    }

    removeFavourite(favouriteId) {
        this.favouriteMealIds = this.favouriteMealIds.filter((id) => id !== favouriteId);

        this.saveFavouriteIds(this.favouriteMealIds);
    }

    loadFavouriteIds() {
        const favouriteIds = localStorage.getItem('favouriteIds');

        if (!favouriteIds) {
            this.saveFavouriteIds([]);
            return [];
        }

        return JSON.parse(favouriteIds);
    }

    saveFavouriteIds(itemIds) {
        localStorage.setItem('favouriteIds', JSON.stringify(itemIds));
    }
}
