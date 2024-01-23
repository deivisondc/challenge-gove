import { PageTitle } from "@/components/PageTitle";
import { FileImportType } from "@/types/FileImportType";
import StatusCard from "./components/StatusCard";
import { format } from "date-fns";

type FileImportDetailsProps = {
  params: {
    id: number
  }
}

export default async function FileImportDetails({ params }: FileImportDetailsProps) {
  const dataRaw = await fetch(`http://localhost:8000/api/files/${params.id}`);
  const response = (await dataRaw.json()) as FileImportType;

  return (
    <>
      <div className="flex flex-col gap-1">
        <div className="flex gap-2 items-center">
          <PageTitle backButtonHref="/files">
            Files
          </PageTitle>
        </div>

        <p className="text-gray-500 text-sm">Filename: {!response ? '...' : `${response.filename} - ${format(response.created_at, 'yyyy-MM-dd - HH:mm:ss')}`}</p>

      </div>

      <StatusCard status={response.status} />
    </>
  )
}