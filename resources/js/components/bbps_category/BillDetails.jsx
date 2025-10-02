import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { ArrowLeft } from "lucide-react"

export default function BillDetails({
  selectedBillData,
  inputValue,
  setInputValue,
  payableData,
  onBack,
  onFetchPayable,
  onPay
}) {
  if (!selectedBillData) return <p>No bill data available.</p>

  return (
    <div className="p-4 border rounded-lg">
      <button onClick={onBack} className="flex items-center text-sm text-blue-600 hover:underline mb-4">
        <ArrowLeft className="h-4 w-4 mr-1" /> Back to Providers
      </button>

      <h3 className="font-medium text-lg">{selectedBillData.billerName}</h3>

      <div className="mt-4">
        <label className="text-sm font-medium">{selectedBillData.billerInputParams.paramInfo.paramName}</label>
        <Input value={inputValue} onChange={(e) => setInputValue(e.target.value)} className="mt-1" />

        {payableData && (
          <div className="p-4 border rounded-lg mt-4 bg-green-200">
            <p><strong>Customer:</strong> {payableData.billerResponse?.customerName}</p>
            <p><strong>Bill No:</strong> {payableData.billerResponse?.billNumber}</p>
            <p><strong>Due Date:</strong> {payableData.billerResponse?.dueDate}</p>
            <p><strong>Amount:</strong> ₹{payableData.billerResponse?.billAmount / 100}</p>
          </div>
        )}

        <Button
          className="w-full mt-6"
          onClick={
            payableData
              ? onPay
              : () => onFetchPayable(selectedBillData.billerId, selectedBillData.billerInputParams.paramInfo.paramName, inputValue)
          }
        >
          {payableData ? "Pay" : "Get Payable Amount"}
        </Button>
      </div>
    </div>
  )
}
