import { Input } from "@/components/ui/input"

export default function ProvidersList({ providers, searchProvider, setSearchProvider, onSelectProvider }) {
  const filteredProviders = providers.filter(
    (provider) =>
      (provider.blr_name || "").toLowerCase().includes(searchProvider.toLowerCase()) ||
      (provider.billerId || "").toLowerCase().includes(searchProvider.toLowerCase()) ||
      (provider.billerCoverage || "").toLowerCase().includes(searchProvider.toLowerCase())
  )

  return (
    <div className="p-4 border rounded-lg">
      <Input
        placeholder="Search by Name / ID / Coverage"
        value={searchProvider}
        onChange={(e) => setSearchProvider(e.target.value)}
        className="mb-4"
      />
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto">
        {filteredProviders.map((provider) => (
          <div
            key={provider.blr_id}
            className="p-4 border rounded-lg cursor-pointer hover:bg-gray-100"
            onClick={() => onSelectProvider(provider.blr_name)}
          >
            <h3 className="font-medium">{provider.blr_name}</h3>
            <p className="text-sm text-muted-foreground">{provider.billerCategory}</p>
            <p className="text-sm text-muted-foreground">{provider.billerCoverage}</p>
          </div>
        ))}
      </div>
    </div>
  )
}
