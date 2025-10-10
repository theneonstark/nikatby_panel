import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { ArrowLeft } from "lucide-react"

export default function BillDetails({
  selectedBillData,
  inputValues,
  setInputValues,
  payableData,
  onBack,
  onFetchPayable,
  onPay,
}) {
  if (!selectedBillData) return <p>No bill data available.</p>

  const billerParams = selectedBillData?.billerInputParams?.paramInfo || []

  // ✅ Handle input change
  const handleChange = (paramName, value) => {
    setInputValues((prev) => ({
      ...prev,
      [paramName]: value,
    }))
  }

  // ✅ Handle fetch payable
  const handleFetch = () => {
    // send all entered values at once
    onFetchPayable(selectedBillData.billerId, inputValues)
  }

  return (
    <div className="p-4 border rounded-lg">
      {/* 🔙 Back Button */}
      <button
        onClick={onBack}
        className="flex items-center text-sm text-blue-600 hover:underline mb-4"
      >
        <ArrowLeft className="h-4 w-4 mr-1" /> Back to Providers
      </button>

      {/* 🔖 Biller Name */}
      <h3 className="font-medium text-lg">{selectedBillData.billerName}</h3>

      <div className="mt-4 space-y-3">
        {/* 🔢 Render all paramInfo fields */}
        {Array.isArray(billerParams) && billerParams.length > 0 ? (
          billerParams.map((param, idx) => (
            <div key={idx}>
              <label className="text-sm font-medium block">
                {param.paramName}
              </label>
              <Input
                type={param.dataType === "NUMERIC" ? "number" : "text"}
                value={inputValues[param.paramName] || ""}
                onChange={(e) => handleChange(param.paramName, e.target.value)}
                minLength={param.minLength}
                maxLength={param.maxLength}
                className="mt-1"
              />
            </div>
          ))
        ) : (
          <div>
            <label className="text-sm font-medium">
              {selectedBillData.billerInputParams?.paramInfo?.paramName}
            </label>
            <Input
              value={
                inputValues[
                  selectedBillData.billerInputParams?.paramInfo?.paramName
                ] || ""
              }
              onChange={(e) =>
                handleChange(
                  selectedBillData.billerInputParams?.paramInfo?.paramName,
                  e.target.value
                )
              }
              className="mt-1"
            />
          </div>
        )}

        {/* 🧾 Bill Info */}
        {payableData && (
          <div className="p-4 border rounded-lg mt-4 bg-green-200">
            <p>
              <strong>Customer:</strong>{" "}
              {payableData.billerResponse?.customerName}
            </p>
            <p>
              <strong>Bill No:</strong>{" "}
              {payableData.billerResponse?.billNumber}
            </p>
            <p>
              <strong>Due Date:</strong> {payableData.billerResponse?.dueDate}
            </p>
            <p>
              <strong>Amount:</strong> ₹
              {payableData.billerResponse?.billAmount / 100}
            </p>
          </div>
        )}

        {/* ⚡ Button */}
        <Button
          className="w-full mt-6"
          onClick={payableData ? onPay : handleFetch}
        >
          {payableData ? "Pay" : "Get Payable Amount"}
        </Button>
      </div>
    </div>
  )
}
