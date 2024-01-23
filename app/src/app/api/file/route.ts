import { readFile } from 'fs/promises';
import path from 'path';

export async function GET() {
  const buffer = await readFile(path.join(process.cwd(), 'src/assets', 'Template.xlsx'));

  const headers = new Headers();
  headers.append('Content-Disposition', 'attachment; filename="Template.xlsx"');

  return new Response(buffer, {
    headers,
  });
}