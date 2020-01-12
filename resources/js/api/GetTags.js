const endpoint = '/api/tags';

export async function get() {
    const response = await axios.get(endpoint);
    return response.data;
}
