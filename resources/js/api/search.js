const endpoint = '/api/search';

export async function search(keyword, currentPage, currentOrder) {
    const response = await axios.get(endpoint, {
        params: {
            keyword: keyword.trim(),
            page: currentPage,
            order: currentOrder,
        }
    });
    return response.data;
}
