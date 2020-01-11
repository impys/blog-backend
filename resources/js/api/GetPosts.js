const endpoint = '/api/posts';

export async function get(currentPage) {
    const response = await axios.get(endpoint, {
        params: {
            page: currentPage,
        }
    });
    return response.data;
}
