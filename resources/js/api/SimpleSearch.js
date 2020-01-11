const endpoint = '/api/simple-search';

export async function search(query) {
    const response = await axios.get(endpoint, {
        params: {
            query: query.trim(),
        },
    });
    return response.data;
}
