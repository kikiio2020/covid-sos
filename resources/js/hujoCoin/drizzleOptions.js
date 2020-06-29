import HujoCoin from './contracts/HujoCoin.json';

//https://www.trufflesuite.com/docs/drizzle/getting-started/contract-interaction

const options = {
  // The contracts to monitor
  contracts: [HujoCoin],
  events: {
    // monitor SimpleStorage.StorageSet events
    Hujo: ['Minted', 'Transfer']
  },
  polls: {
    // check accounts ever 15 seconds
    accounts: 15000
  }
}

export default options