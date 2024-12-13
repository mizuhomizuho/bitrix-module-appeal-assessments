
class Ltd8Ratings {

    #starsItemSelector = '.js-ltd8-ratings__stars-item';

    constructor() {
        document.addEventListener('DOMContentLoaded', () => {
            this.#init();
        });
    }

    #init = () => {
        if (!document.querySelector(this.#starsItemSelector)) {
            return;
        }
        this.#bindSelectItem();
    }

    #bindSelectItem = () => {
        document.querySelector(this.#starsItemSelector).forEach((el) => {
            el.addEventListener('click', () => {
                this.#send();
            });
        });
    }

    #send = (requestNumber, ) => {
        BX.ajax.runAction('ltd8:ratings.Stars.add', {
            data: {
                folderId: 1
            }
        });
    }

}

new Ltd8Ratings();
