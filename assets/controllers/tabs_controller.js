import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['tab', 'tabContent'];

    static values = {
        currentTabId: String
    }

    currentTabIdValueChanged() {
        this.tabTargets.forEach((item) => {
           if (item.dataset.tabId === this.currentTabIdValue) {
               item.classList.add('tab-active');
           } else {
               item.classList.remove('tab-active');
           }
        });

        this.tabContentTargets.forEach((item) => {
            if (item.dataset.tabId === this.currentTabIdValue) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }

    selectTab(event) {
        this.currentTabIdValue = event.currentTarget.dataset.tabId;
    }
}