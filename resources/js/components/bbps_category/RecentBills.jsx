import { Input } from "@/components/ui/input"
import { Badge } from "@/components/ui/badge"

export default function RecentBills({ bills, searchBill, setSearchBill }) {
  const filteredBills = bills.filter(
    (bill) =>
      bill.consumerNo.toLowerCase().includes(searchBill.toLowerCase()) ||
      bill.provider.toLowerCase().includes(searchBill.toLowerCase()) ||
      bill.status.toLowerCase().includes(searchBill.toLowerCase())
  )

  return (
    <div className="p-4 border rounded-lg">
      <Input
        placeholder="Search by Consumer No / Provider / Status"
        value={searchBill}
        onChange={(e) => setSearchBill(e.target.value)}
        className="mb-4"
      />
      <div className="space-y-4 max-h-[400px] overflow-y-auto">
        {filteredBills.map((bill) => (
          <div key={bill.id} className="flex items-center justify-between p-3 border rounded-lg">
            <div>
              <p className="font-medium">{bill.consumerNo}</p>
              <p className="text-sm text-muted-foreground">
                {bill.provider} • Due: {bill.dueDate}
              </p>
            </div>
            <div className="text-right">
              <p className="font-medium">{bill.amount}</p>
              <Badge variant={bill.status === "Paid" ? "default" : bill.status === "Pending" ? "secondary" : "destructive"}>
                {bill.status}
              </Badge>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}
