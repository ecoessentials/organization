import Sortable from 'stimulus-sortable';
import patch from "stimulus-sortable";

export default class extends Sortable {
    connect() {
        super.connect();
        if (this.element.dataset.sortableGroup !== undefined) {
            this.sortable.option('group', this.element.dataset.sortableGroup);
        }
    }

    async end({item, newIndex, to}) {
        if (!item.dataset.sortableCollectionUpdateUrl)
            return;
        await fetch(item.dataset.sortableCollectionUpdateUrl, {
            method: "POST",
            body: JSON.stringify({
                position: newIndex,
                dest: + to.dataset.sortableCollectionId
            })
        })
    }
}