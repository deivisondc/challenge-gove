'use client'

import { Button } from "@/components/ui/button";

const TemplateFileButton = () => {
  function onDownload() {
    fetch('/api/file')
      .then(res => res.blob())
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Template.xlsx';
        link.click();
        window.URL.revokeObjectURL(url);
      })
  }

  return (
    <>
      <Button onClick={onDownload} variant="ghost">
        Download template file
      </Button>
    </>
  );
};

export { TemplateFileButton };