import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['item'];

    static values = {
        currentId: String
    }

    currentIdValueChanged() {
        this.itemTargets.forEach((item) => {
           if (item.dataset.listItemId === this.currentIdValue) {
               item.classList.add('btn-active');
           } else {
               item.classList.remove('btn-active');
           }
        });
    }

    selectItem(event) {
        this.currentIdValue = event.currentTarget.dataset.listItemId;
    }

    deselect() {
        this.currentIdValue = null;
    }
}