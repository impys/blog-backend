const endpoint = '/api/search';

export async function search(query, currentPage = 1, type, filter) {
    const response = await axios.get(endpoint, {
        params: {
            query: query.trim(),
            page: currentPage,
            type: type,
            filter: filter,
        }
    });
    return response.data;
}
