import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from "@/components/ui/dialog"
import { Button } from "@/components/ui/button"

export default function PayDialog({ open, onClose, payableData, isPaying, paymentError, paymentSuccess, onConfirmPay }) {
  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>Confirm Payment</DialogTitle>
          <DialogDescription>Please review your bill details before making the payment.</DialogDescription>
        </DialogHeader>

        {payableData && (
          <div className="space-y-2 text-sm">
            <p><strong>Customer:</strong> {payableData.billerResponse?.customerName}</p>
            <p><strong>Bill No:</strong> {payableData.billerResponse?.billNumber}</p>
            <p><strong>Due Date:</strong> {payableData.billerResponse?.dueDate}</p>
            <p><strong>Amount:</strong> ₹{payableData.billerResponse?.billAmount / 100}</p>
          </div>
        )}

        <DialogFooter>
          <Button variant="outline" onClick={() => onClose(false)}>Cancel</Button>
          <Button onClick={onConfirmPay} disabled={isPaying}>
            {isPaying ? "Processing..." : "Confirm & Pay"}
          </Button>
        </DialogFooter>

        {paymentError && <p className="text-sm text-red-600 mt-2">{paymentError}</p>}
        {paymentSuccess && <p className="text-sm text-green-600 mt-2">Payment successful ✅</p>}
      </DialogContent>
    </Dialog>
  )
}
