const endpoint = '/api/search';

export async function search(keyword, currentPage, currentRanking) {
    const response = await axios.get(endpoint, {
        params: {
            keyword: keyword.trim(),
            page: currentPage,
            ranking: currentRanking,
        }
    });
    return response.data;
}
