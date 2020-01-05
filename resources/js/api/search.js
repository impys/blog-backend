const endpoint = '/api/search';

export async function search(query, currentPage = 1) {
    const response = await axios.get(endpoint, {
        params: {
            query: query.trim(),
            page: currentPage,
        }
    });
    return response.data;
}
