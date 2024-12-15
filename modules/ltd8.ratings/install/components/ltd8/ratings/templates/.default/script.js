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
        document.querySelectorAll(this.#starsItemSelector).forEach((el) => {
            el.addEventListener('click', (e) => {
                this.#send(e.currentTarget);
                this.#setActive(e.currentTarget);
            });
        });
    }

    #setActive = (currentTarget) => {
        document.querySelectorAll(
            `${this.#starsItemSelector}[data-request-number="${
                currentTarget.dataset.requestNumber
            }"][data-criterion-id="${
                currentTarget.dataset.criterionId
            }"]`
        ).forEach((el) => {
            delete el.dataset.active;
        });
        currentTarget.dataset.active = 'true';
    }

    #send = (currentTarget) => {
        BX.ajax.runAction('ltd8:ratings.Stars.add', {
            data: {
                criterionId: currentTarget.dataset.criterionId,
                requestNumber: currentTarget.dataset.requestNumber,
                stars: currentTarget.dataset.stars,
            }
        });
    }

}

new Ltd8Ratings();
