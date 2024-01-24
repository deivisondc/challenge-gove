const baseUrl = process.env.NEXT_PUBLIC_API_BASE_URL;

type ApiParams = {
    resource: string
    queryParams?: string
    method?: 'GET' | 'PUT' | 'POST' | 'DELETE'
    body?: Record<string, unknown> | FormData;
}

const isStatus200 = (status: number) => status >= 200 && status < 300;

export const apiFetch = async <T,>({ resource, queryParams, method = 'GET', body }: ApiParams) => { 
    let fullResource = resource.startsWith('/') ? '' : '/'
    fullResource += resource
    let fullQueryParams = queryParams?.startsWith('?') ? '' : '?'
    fullQueryParams += queryParams

    const formattedBody = body instanceof FormData ? body : JSON.stringify(body)

    const dataRaw = await fetch(`${baseUrl}${fullResource}${fullQueryParams}`, {
        method,
        body: formattedBody
    });

    if (dataRaw.ok && isStatus200(dataRaw.status)) {
        const data = (await dataRaw.json()) as T
        return data;
    }

    throw new Error('Failed fetching data.')
}