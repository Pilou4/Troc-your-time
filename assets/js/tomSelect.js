import TomSelect from 'tom-select';
async function jsonFetch(url) {
    const response = await fetch(url, {
        headers: {
            Accept: "application/json",
        },
    });
    if (response.status === 204) {
    return null;
    }
    if (response.ok) {
        return await response.json();
    }
    throw response;
}

/**
 * @param {HTMLSelectElement} select
 */
function bindSelect(select) {
    new TomSelect(select, {
        hideSelected: true,
        closeAfterSelect: true,
        valueField: select.dataset.value,
        labelField: select.dataset.label,
        searchField: select.dataset.label,
        plugins: {
            remove_button: { title: "Supprimer cet élément" },
        },
        load: async (query, callback) => {
            const url = `${select.dataset.remote}?q=${encodeURIComponent(query)}`;
            console.log(url);
            callback(await jsonFetch(url));
        },
    });
}

Array.from(document.querySelectorAll("select[multiple]")).map(bindSelect);
