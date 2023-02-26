import * as bootstrap from "bootstrap";

export default class Tooltip {
    static init = () => {
        const tooltipTriggerList = document.querySelectorAll(
            '[data-bs-toggle="tooltip"]'
        );
        if (tooltipTriggerList) {
            const tooltipArr = Array.from(tooltipTriggerList);
            tooltipArr.map((tooltipTriggerEl) => {
                const tooltip = new bootstrap.Tooltip(tooltipTriggerEl);
                tooltipTriggerEl.addEventListener("shown.bs.tooltip", () =>
                    setTimeout(() => {
                        tooltip.hide();
                    }, 1000)
                );
            });
        }
    };

    static dispose = () => {
        const tooltipTriggerList = document.querySelectorAll(
            '[data-bs-toggle="tooltip"]'
        );
        if (tooltipTriggerList) {
            const tooltipArr = Array.from(tooltipTriggerList);
            tooltipArr.map((tooltipTriggerEl) =>
                bootstrap.Tooltip.getOrCreateInstance(
                    tooltipTriggerEl
                ).dispose()
            );
        }
    };
}
