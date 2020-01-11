export function getScrollBottom() {
    let offsetHeight = Math.max(
        document.body.scrollHeight,
        document.body.offsetHeight
    );
    let clientHeight =
        window.innerHeight ||
        document.documentElement.clientHeight ||
        document.body.clientHeight ||
        0;
    let scrollTop =
        window.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop ||
        0;

    return offsetHeight - clientHeight - scrollTop;
}

export function canLoadMore() {
    return getScrollBottom() <= 300
}

export function getCurrentByMeta(meta) {
    if (!meta) {
        return 1;
    }

    if (meta.current_page == meta.last_page) {
        return meta.last_page;
    }
    return meta.current_page + 1;
}

export function isLastPageByMeta(meta) {
    return meta && meta.current_page == meta.last_page;
}

export function handleScroll(vm, getDataMethod = 'get') {
    if (!vm.$options.name) {
        throw "component name is required";
    }

    if (vm.$options.name !== vm.$route.name) {
        return;
    }

    if (vm.isLastPage) {
        return
    }

    if (!canLoadMore()) {
        return;
    }

    eval(`vm.${getDataMethod}()`)
}
