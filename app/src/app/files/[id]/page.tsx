import { PageTitle } from "@/components/PageTitle";
import { FileImportType } from "@/types/FileImportType";
import NotificationsTable from "./components/Notifications/NotificationsTable";
import FileImportErrorsTable from "./components/FileImportErrors/FileImportErrorTable";
import StatusCard from "./components/StatusCard";
import { format } from "date-fns";
import { ExceptionBoundary } from "@/components/ExceptionBoundary";
import { apiFetch } from "@/service/api";
import { TableSkeleton } from "@/components/DataTable/Skeleton";

type FileImportDetailsProps = {
  params: {
    id: number
  }
}

export default async function FileImportDetails({ params }: FileImportDetailsProps) {
  let response, error = '';
  try {
    response = await apiFetch<FileImportType>({ resource: `/files/${params.id}` })
  } catch (err) {
    if (err instanceof Error) {
      error = err.message
    }
  }

  return (
    <>
      <div className="flex gap-2 items-center">
        <PageTitle backButtonHref="/files">
          Files
        </PageTitle>
      </div>

      <ExceptionBoundary error={error}>
        <>
          <p className="mt-1 text-gray-500 text-sm">Filename: {!response ? '...' : `${response.filename} - ${format(response.created_at, 'yyyy-MM-dd - HH:mm:ss')}`}</p>
          {response && (
            <>
              <StatusCard status={response.status} />
              
              <NotificationsTable fileImportId={params.id} />
              <FileImportErrorsTable fileImportId={params.id} />
            </>
            )}
        </>
      </ExceptionBoundary>

    </>
  )
}