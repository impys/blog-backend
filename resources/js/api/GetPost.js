const endpoint = '/api/posts';

export async function get(id) {
    const response = await axios.get(endpoint + '/' + id);
    return response.data;
}
