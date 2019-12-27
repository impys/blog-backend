const endpoint = `/search`;

export async function search(query, currentPage) {
    const response = await axios.get(endpoint, {
        params: {
            query: query.trim(),
            page: currentPage,
        }
    });
    return response.data;
}
