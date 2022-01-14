import FingerprintJS from "@fingerprintjs/fingerprintjs";

async function generateFp() {
  // Initialize an agent at application startup.
  const fpPromise = FingerprintJS.load();

  return await (async () => {
    // Get the visitor identifier when you need it.
    const fp = await fpPromise;
    const result = await fp.get();

    // This is the visitor identifier:
    return result.visitorId;
  })();
}

export default generateFp;
